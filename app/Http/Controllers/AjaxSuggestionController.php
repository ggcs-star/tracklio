<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AjaxSuggestionController extends Controller
{
   public function searchLocation(Request $request)
   {
       $query = $request->get('q', '');
       
       if (strlen($query) < 2) {
           return response()->json([]);
       }
       
       $results = [];
       
       try {
           $response = Http::timeout(10)->retry(2, 1000)->withHeaders([
               'User-Agent' => 'SocialMediaApp/1.0'
           ])->get("https://nominatim.openstreetmap.org/search", [
               'q' => $query . ', Ahmedabad, Gujarat, India',
               'format' => 'json',
               'limit' => 10,
               'addressdetails' => 1,
               'accept-language' => 'en',
               'countrycodes' => 'in',
           ]);
           
           if ($response->successful()) {
               $data = $response->json();
               
               foreach ($data as $place) {
                   $results[] = [
                       'id' => 'osm_' . $place['place_id'],
                       'name' => $this->formatPlaceName($place),
                       'full_address' => $place['display_name'],
                       'location' => $place['address']['city'] ?? $place['address']['state'] ?? '',
                       'category' => $place['type'] ?? 'Location',
                       'checkin_text' => '📍 Location',
                       'is_verified' => false,
                   ];
               }
           }
           
       } catch (\Exception $e) {
           Log::error('Location search failed: ' . $e->getMessage());
       }
       
       $seen = [];
       $uniqueResults = [];
       foreach ($results as $result) {
           $key = strtolower($result['name']);
           if (!in_array($key, $seen)) {
               $seen[] = $key;
               $uniqueResults[] = $result;
           }
       }
       
       return response()->json($uniqueResults);
   }
   
   private function formatPlaceName($place)
   {
       $name = $place['name'];
       $address = $place['address'] ?? [];
       $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? '';
       
       if ($city && $city != $name) {
           return $name . ', ' . $city;
       }
       return $name;
   }
   
   public function searchHashtag(Request $request)
   {
       $query = $request->get('q', '');
       
       if (strlen($query) < 2) {
           return response()->json([]);
       }
       
       $results = [];
       $seenNames = [];
       
       $instagramAccount = \App\Models\SocialAccount::where('user_id', (string) auth()->id())
           ->where('platform', 'instagram')
           ->where('status', 'connected')
           ->first();
       
       if ($instagramAccount && isset($instagramAccount->credentials['ig_business_id'])) {
           $igBusinessId = $instagramAccount->credentials['ig_business_id'];
           $accessToken = $instagramAccount->credentials['access_token'] ?? 
                         $instagramAccount->credentials['user_access_token'] ?? null;
                         
           if ($accessToken) {
               try {
                   $response = Http::timeout(5)->get("https://graph.facebook.com/v18.0/ig_hashtag_search", [
                       'user_id' => $igBusinessId,
                       'q' => $query,
                       'access_token' => $accessToken
                   ]);
                   
                   if ($response->successful()) {
                       $data = $response->json();
                       foreach ($data['data'] ?? [] as $tag) {
                           $name = $tag['name'];
                           if (!in_array($name, $seenNames)) {
                               $seenNames[] = $name;
                               $results[] = [
                                   'name' => $name,
                                   'count' => $tag['media_count'] ?? 0
                               ];
                           }
                       }
                   }
               } catch (\Exception $e) {
                   Log::error('Instagram hashtag search failed: ' . $e->getMessage());
               }
           }
       }
       
       if (empty($results)) {
           $suggestions = [
               ['name' => $query, 'count' => rand(10000, 5000000)],
               ['name' => $query . 'love', 'count' => rand(10000, 5000000)],
               ['name' => $query . 'day', 'count' => rand(10000, 5000000)],
               ['name' => $query . 'life', 'count' => rand(10000, 5000000)],
               ['name' => $query . 'style', 'count' => rand(10000, 5000000)],
               ['name' => $query . 'gram', 'count' => rand(10000, 5000000)],
               ['name' => $query . 'photography', 'count' => rand(10000, 5000000)],
           ];
           
           foreach ($suggestions as $tag) {
               if (!in_array($tag['name'], $seenNames)) {
                   $seenNames[] = $tag['name'];
                   $results[] = $tag;
               }
           }
       }
       
       return response()->json($results);
   }
      public function searchMention(Request $request)
   {
       $query = $request->get('q', '');
       
       $results = [];
       
       $facebookAccount = \App\Models\SocialAccount::where('user_id', (string) auth()->id())
           ->where('platform', 'facebook')
           ->where('status', 'connected')
           ->first();
       
       if ($facebookAccount && !empty($facebookAccount->pages)) {
           foreach ($facebookAccount->pages as $page) {
               if (empty($query) || stripos($page['page_name'] ?? '', $query) !== false) {
                   $results[] = [
                       'id' => $page['page_id'],
                       'name' => $page['page_name'] ?? 'Facebook Page',
                       'type' => 'facebook_page',
                       'avatar' => substr($page['page_name'] ?? 'P', 0, 1),
                       'platform' => 'facebook'
                   ];
               }
           }
       }
       
       return response()->json($results);
   }
}
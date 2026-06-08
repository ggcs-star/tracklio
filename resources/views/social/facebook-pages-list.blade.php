<!DOCTYPE html>
<html>
<head>
    <title>Select Pages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-xl w-[500px] max-w-[90%] overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="font-semibold">Choose the Pages you want new2025 to access</h3>
            <p class="text-sm text-gray-500 mt-1">Later, you'll be able to review what new2025 will be able to do with the Pages you select.</p>
        </div>
        
        <div class="p-4 space-y-3">
            <div class="border rounded-lg p-3">
                <label class="flex items-center gap-2">
                    <input type="radio" name="opt_type" value="all" id="opt_all">
                    <span class="font-medium">Opt in to all current and future Pages</span>
                </label>
                <p class="text-xs text-gray-500 ml-6">This will give new2025 access to your current Pages, in addition to any Pages that you create in the future.</p>
            </div>
            
            <div class="border rounded-lg p-3">
                <label class="flex items-center gap-2">
                    <input type="radio" name="opt_type" value="current" id="opt_current" checked>
                    <span class="font-medium">Opt in to current Pages only</span>
                </label>
                <p class="text-xs text-gray-500 ml-6">This will only give new2025 access to the Pages you select.</p>
            </div>
            
            <div class="mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium">Select all</span>
                    <span class="text-sm text-gray-500" id="selectedCount">0 assets selected</span>
                </div>
                
                <div class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-2">
                    @foreach($pages as $page)
                    <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer page-item">
                        <input type="checkbox" class="page-checkbox" value="{{ json_encode(['id' => $page['id'], 'name' => $page['name'], 'access_token' => $page['access_token']]) }}">
                        <div>
                            <div class="font-medium">{{ $page['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $page['id'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t flex justify-end gap-2">
            <button onclick="window.close()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
            <button onclick="savePages()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
        </div>
    </div>

    <script>

var userToken = '{{ $userToken }}';
var profileName = '{{ $profileName ?? "Facebook Profile" }}';

function updateSelectedCount() {

    var checked =
        document.querySelectorAll('.page-checkbox:checked').length;

    document.getElementById('selectedCount').innerText =
        checked + ' assets selected';
}

document.querySelectorAll('.page-checkbox').forEach(function(cb) {

    cb.addEventListener('change', updateSelectedCount);

});

document.getElementById('opt_all').addEventListener('change', function() {

    if (this.checked) {

        document.querySelectorAll('.page-checkbox').forEach(function(cb) {

            cb.checked = true;

        });

        updateSelectedCount();
    }
});

document.getElementById('opt_current').addEventListener('change', function() {

    if (this.checked) {

        document.querySelectorAll('.page-checkbox').forEach(function(cb) {

            cb.checked = false;

        });

        updateSelectedCount();
    }
});

function savePages() {

    var selectedPages = [];

    document.querySelectorAll('.page-checkbox:checked').forEach(function(cb) {

        selectedPages.push(JSON.parse(cb.value));

    });

    fetch('/facebook/save-pages', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}',

            'Accept': 'application/json'

        },

        body: JSON.stringify({
            pages: selectedPages,
            user_token: userToken,
            profile_name: profileName
        })

    })

    .then(function(res) {

        return res.json();

    })

    .then(function(data) {

       if (data.success) {

            window.location.href = '/accounts';

        } else {

            alert(data.error || 'Something went wrong');

        }

    })

    .catch(function(err) {

        console.log(err);

        alert('Save failed');

    });

}

updateSelectedCount();

</script>
</body>
</html>
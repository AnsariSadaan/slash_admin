<?= view('header') ?>
<div class="flex justify-center p-2">
    <a class="px-4 py-2">Dashboard</a>
    <a class="px-4 py-2">Live</a>
    <a class="px-4 py-2">Reports</a>
    <a class="px-4 py-2">Conversation</a>
    <a class="px-4 py-2">Contacts</a>
    <div class="dropdown p-2">
        <a class="px-4 py-2" href="#">Operation</a>
        <div class="dropdown-content">
            <a href="/dashboard">Users</a>
            <a href="/accesslevel">Access Level</a>
            <a href="/campaign">Campaign</a>
            <a href="/showCampaign">show campaign</a>
            <a href="/chat">chat</a>
        </div>
    </div>
    <a class="px-4 py-2">Advanced Settings</a>
    <a class="px-4 py-2">Custom Reports</a>
</div>
<div class="bg-gray-500 p-3">
    <p class="text-white">Operation</p>
</div>
<div class="bg-gray-100 flex justify-center items-center h-[80%]">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <!-- Logout Link -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-center text-gray-800">Campaign Details</h1>
        </div>

        <!-- Table Start -->
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-indigo-600 text-white">
                    <th class="px-4 py-2 text-center">ID</th>
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Description</th>
                    <th class="px-4 py-2 text-center">Client</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaign as $row) {
                    // echo print_r($campaign); die;
                ?>

                    <tr class="border-b">
                        <td class="px-4 py-2 text-center"><?php echo $row->id; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $row->name; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $row->description; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $row->client; ?></td>
                        <td class="px-4 py-2 text-center">
                            <!-- Edit Button with Data -->
                            <button
                                class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                                onclick="openEditModal(<?php echo $row->id; ?>, '<?php echo $row->name; ?>', '<?php echo $row->description; ?>' , '<?php echo $row->client; ?>')">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>

                            <!-- Delete Button with Data -->
                            <button
                                class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                onclick="confirmDelete(<?php echo $row->id; ?>)">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Table End -->

        <!-- pagination start -->
        <div class="flex justify-center mt-6">
            <nav aria-label="Page navigation example">
                <ul class="inline-flex items-center -space-x-px">
                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <li>
                            <a href="/showCampaign?page=<?php echo $currentPage - 1; ?>&searchQuery=<?php echo urlencode($searchQuery); ?>" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 rounded-l-lg">
                                Previous
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Pagination Links -->
                    <?php
                    // Determine the page range to display
                    $pageRange = 2;
                    $startPage = max(1, $currentPage - floor($pageRange / 2));
                    $endPage = min($totalPages, $currentPage + floor($pageRange / 2));

                    // Add "Previous Ellipsis" if the range starts before 1
                    if ($startPage > 1) {
                        echo '<li><a href="/showCampaign?page=1&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li><span class="px-4 py-2 text-gray-400">...</span></li>';
                        }
                    }

                    // Loop through the pages
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        echo '<li>';
                        echo '<a href="/showCampaign?page=' . $i . '&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 ' . ($i == $currentPage ? 'font-bold' : '') . '">';
                        echo $i;
                        echo '</a>';
                        echo '</li>';
                    }

                    // Add "Next Ellipsis" if the range ends before the last page
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li><span class="px-4 py-2 text-gray-400">...</span></li>';
                        }
                        echo '<li><a href="/showCampaign?page=' . $totalPages . '&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <li>
                            <a href="/showCampaign?page=<?php echo $currentPage + 1; ?>&searchQuery=<?php echo urlencode($searchQuery); ?>" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 rounded-r-lg">
                                Next
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- pagination end -->

    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="absolute w-full m-auto flex bg-gray-500 bg-opacity-50 hidden h-screen justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Edit User</h2>
            <form id="editForm" action="/update-campaign" method="POST">
                <div class="mb-4">
                    <label for="editId" class="block text-gray-700">Id</label>
                    <input type="number" name="id" id="editId" class="w-full p-2 border border-gray-300 rounded mt-2" readonly>
                </div>
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="editName" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="mb-4">
                    <label for="editDescription" class="block text-gray-700">Description</label>
                    <input type="description" name="description" id="editDescription" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="mb-4">
                    <label for="editClient" class="block text-gray-700">Client</label>
                    <input type="client" name="client" id="editClient" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Open the edit modal and pre-fill the form
    function openEditModal(id, name, description, client) {
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editClient').value = client;
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Close the edit modal
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Confirm deletion and send delete request
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            // Send DELETE request to backend
            window.location.href = '/delete-campaign/' + id;
        }
    }
</script>


<?= view('footer'); ?>
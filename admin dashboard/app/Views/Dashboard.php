    <div class="bg-gray-500 p-3">
        <p class="text-white">Operation</p>
    </div>
    <div class="bg-gray-100 flex justify-center items-center h-[80%]">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <!-- Logout Link -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-center text-gray-800">User Details</h1>
                <button id="addUser" onclick="openAddModal()">+</button>
            </div>

            <!-- Table Start -->
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-indigo-600 text-white">
                        <th class="px-4 py-2 text-center">ID</th>
                        <th class="px-4 py-2 text-center">Name</th>
                        <th class="px-4 py-2 text-center">Email</th>
                        <th class="px-4 py-2 text-center">Roles</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) {
                        // print_r($users); die;
                    ?>

                        <tr class="border-b">
                            <td class="px-4 py-2 text-center"><?php echo $user->id; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->name; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->email; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->roles; ?></td>
                            <td class="px-4 py-2 text-center">
                                <!-- Edit Button with Data -->
                                <button
                                    class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                                    onclick="openEditModal(<?php echo $user->id; ?>, '<?php echo $user->name; ?>', '<?php echo $user->email; ?>' , '<?php echo $user->roles; ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>

                                <!-- Delete Button with Data -->
                                <button
                                    class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    onclick="confirmDelete(<?php echo $user->id; ?>)">
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
                            <a href="/dashboard?page=<?php echo $currentPage - 1; ?>&searchQuery=<?php echo urlencode($searchQuery); ?>" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 rounded-l-lg">
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
                        echo '<li><a href="/dashboard?page=1&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li><span class="px-4 py-2 text-gray-400">...</span></li>';
                        }
                    }
                    // Loop through the pages
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        echo '<li>';
                        echo '<a href="/dashboard?page=' . $i . '&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 ' . ($i == $currentPage ? 'font-bold' : '') . '">';
                        echo $i;
                        echo '</a>';
                        echo '</li>';
                    }

                    // Add "Next Ellipsis" if the range ends before the last page
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li><span class="px-4 py-2 text-gray-400">...</span></li>';
                        }
                        echo '<li><a href="/dashboard?page=' . $totalPages . '&searchQuery=' . urlencode($searchQuery) . '" class="px-4 py-2 text-indigo-600 hover:text-indigo-900">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <li>
                            <a href="/dashboard?page=<?php echo $currentPage + 1; ?>&searchQuery=<?php echo urlencode($searchQuery); ?>" class="px-4 py-2 text-indigo-600 hover:text-indigo-900 rounded-r-lg">
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
                <form id="editForm" action="/update-user" method="POST">
                    <div class="mb-4">
                        <label for="editId" class="block text-gray-700">Id</label>
                        <input type="number" name="id" id="editId" class="w-full p-2 border border-gray-300 rounded mt-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="editName" class="block text-gray-700">Name</label>
                        <input type="text" name="name" id="editName" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="editEmail" class="block text-gray-700">Email</label>
                        <input type="email" name="email" id="editEmail" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- add user moal  -->
            <div id="addModal" class="absolute w-full m-auto flex bg-gray-500 bg-opacity-50 hidden h-screen justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Add User</h2>
        <form id="addForm" action="<?= base_url('/adduser') ?>" method="POST">
            <div class="mb-4">
                <label for="addName" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="addName" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addEmail" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="addEmail" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addPassword" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="addPassword" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addRole" class="block text-gray-700">Role</label>
                <select name="roles" class="px-4 py-2">
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['roles']; ?>">
                                            <?= ucfirst($role['roles']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="closeAddModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- add user moal  -->
            <div id="addModal" class="absolute w-full m-auto flex bg-gray-500 bg-opacity-50 hidden h-screen justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Add User</h2>
        <form id="addForm" action="<?= base_url('/adduser') ?>" method="POST">
            <div class="mb-4">
                <label for="addName" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="addName" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addEmail" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="addEmail" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addPassword" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="addPassword" class="w-full p-2 border border-gray-300 rounded mt-2" required>
            </div>
            <div class="mb-4">
                <label for="addRole" class="block text-gray-700">Role</label>
                <select name="roles" class="px-4 py-2">
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['roles']; ?>">
                                            <?= ucfirst($role['roles']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="closeAddModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
            </div>
        </form>
    </div>
</div>

    </div>

                        
    <script>
        // Open the edit modal and pre-fill the form
        function openEditModal(id, name, email) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
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
                window.location.href = '/delete-user/' + id;
            }
        }


        // Open the Add User Modal
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    // Close the Add User Modal
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }
    </script>

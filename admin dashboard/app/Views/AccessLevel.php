<div class="bg-gray-100 flex justify-center items-center h-[80%]">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <?php if($loggedInUser->roles === 'admin'): ?>
            <h1 class="text-3xl font-semibold text-center text-gray-800">Change User Role</h1>
            <?php elseif(($loggedInUser->roles === 'user' || $loggedInUser->roles === 'supervisor' || $loggedInUser->roles === 'teamleader') && $loggedInUser->roles !== 'admin'): ?>
            <h1 class="text-3xl font-semibold text-center text-gray-800">Access Level</h1>
            <?php endif; ?>

        </div>

        <!-- Table Start -->
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-indigo-600 text-white">
                    <th class="px-4 py-2 text-center">ID</th>
                    <th class="px-4 py-2 text-center">Name</th>
                    <th class="px-4 py-2 text-center">Email</th>
                    <th class="px-4 py-2 text-center">Roles</th>
                    <?php if($loggedInUser->roles === 'admin'): ?>
                    <th class="px-4 py-2 text-center">Update Role</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-center"><?php echo $user->id; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $user->name; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $user->email; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $user->roles; ?></td>
                        
                        <?php if($loggedInUser->roles === 'admin'): ?>
                        <td class="px-4 py-2 text-center">
                            <form action="/update-role/<?php echo $user->id; ?>" method="POST">
                                <select name="roles" class="px-4 py-2" onchange="this.form.submit()">
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['roles']; ?>" <?php echo ($user->roles === $role['roles']) ? 'selected' : ''; ?>>
                                            <?= ucfirst($role['roles']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                                <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Table End -->
    </div>
</div>

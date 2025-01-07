<div class="bg-gray-100 flex justify-center items-center h-[80%]">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <!-- Logout Link -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-center text-gray-800">Audit Logs</h1>
        </div>

        <!-- Table Start -->
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-indigo-600 text-white">
                    <!-- <th class="px-4 py-2 text-center">ID</th> -->
                    <th class="px-4 py-2 text-center">DateTime</th>
                    <th class="px-4 py-2 text-center">Action</th>
                    <th class="px-4 py-2 text-center">UserName</th>
                    <th class="px-4 py-2 text-center">logs</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditlog as $logs) {
                    // echo print_r($campaign); die;
                ?>
                    <tr class="border-b">
                        <!-- <td class="px-4 py-2 text-center"><?php echo $logs->id; ?></td> -->
                        <td class="px-4 py-2 text-center"><?php echo $logs->datetime; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $logs->action; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $logs->name; ?></td>
                        <td class="px-4 py-2 text-center"><?php echo $logs->logs; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Table End -->

    </div>
                </div>
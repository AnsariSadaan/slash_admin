<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Add New Campaign</h2>

        <!-- Error message -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Campaign Form -->
        <form action="/campaign/store" method="post" class="space-y-3">
            <?= csrf_field() ?>

            <!-- Campaign Name Field -->
            <div class="form-group">
                <label for="name" class="block text-sm font-medium text-gray-700">Campaign Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <!-- Description Field -->
            <div class="form-group">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="2" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>

            <!-- Client Field -->
            <div class="form-group">
                <label for="client" class="block text-sm font-medium text-gray-700">Client</label>
                <input type="text" id="client" name="client" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex items-center justify-center space-x-4">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Campaign</button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="<?= site_url('campaign') ?>" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
        </div>

        <div class="mt-6 text-center">
            <a href="/showCampaign" class="text-indigo-600 hover:text-indigo-800 text-lg">Show Campaign Details</a>
        </div>
    </div>
</div>

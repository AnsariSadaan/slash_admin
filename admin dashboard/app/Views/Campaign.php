
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Campaign</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Campaign</h2>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <form action="/campaign/store" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="name">Campaign Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" class="form-control" id="client" name="client" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Campaign</button>
            <a href="<?= site_url('campaign') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <div>
        <a href="/showCampaign">Show Campaign Details</a>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Chat Dashboard</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body class="font-sans bg-gray-100">
    <nav class="bg-black text-white px-4 py-3 flex justify-between items-center">
        <div>
            <img src="" alt="logo" class="w-10">
        </div>
        <div class="flex items-center space-x-4">
            <h1 class="text-lg font-medium"><?= ucfirst(
                                                session()->get('user')->name
                                            ) ?></h1>
            <a href="/logout" class="text-white px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700">Logout</a>
        </div>
    </nav>


    <div class="flex justify-center p-2">
        <?php if (session()->get('user')->roles === 'admin'): ?>
            <a class="px-4 py-2">Dashboard</a>
            <a class="px-4 py-2">Live</a>
            <a class="px-4 py-2">Reports</a>
            <div class="dropdown p-2">
                <a class="px-4 py-2">Conversation</a>
                <div class="dropdown-content">
                    <a href="/chat">Chat</a>
                </div>
            </div>
            <a class="px-4 py-2">Contacts</a>
            <div class="dropdown p-2">
                <a class="px-4 py-2" href="#">Operation</a>
                <div class="dropdown-content">
                    <a href="/dashboard">Users</a>
                    <a href="/accesslevel">Access Level</a>
                    <a href="/showCampaign">Campaign</a>
                </div>

            </div>
            <a class="px-4 py-2">Advanced Settings</a>
            <a class="px-4 py-2">Custom Reports</a>


        <?php elseif ((session()->get('user')->roles === 'user') || (session()->get('user')->roles === 'supervisor') || (session()->get('user')->roles === 'teamleader')): ?>
            <a class="px-4 py-2">Dashboard</a>
            <div class="dropdown p-2">
                <a class="px-4 py-2">Conversation</a>
                <div class="dropdown-content">
                    <a href="/chat">Chat</a>
                </div>
            </div>
            <div class="dropdown p-2">
                <a class="px-4 py-2" href="#">Operation</a>
                <div class="dropdown-content">
                    <a href="/dashboard">Users</a>
                    <a href="/accesslevel">Access Level</a>
                    <a href="/showCampaign">Campaign</a>
                </div>

            </div>
        <?php endif; ?>
    </div>
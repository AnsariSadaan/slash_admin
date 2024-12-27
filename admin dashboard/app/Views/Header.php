<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Dashboard</title>
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

<body class="">
    <nav class="flex justify-between bg-black text-white px-2 py-3">
        <div>
            <img src="" alt="logo">
        </div>
        <div class="flex">
            <h1 class="text-lg px-2"><?php print_r(ucfirst(session()->get('user')->name)) ?></h1>
            <a href="/logout" class="text-white px-4 py-1 rounded-lg bg-red-600 inline-block">Logout</a>
        </div>
    </nav>
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
    
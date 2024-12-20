<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
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

    
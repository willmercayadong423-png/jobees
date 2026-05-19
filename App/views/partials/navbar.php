<?php 
use Framework\Session;
?>

<!-- Nav -->
<header class="bg-blue-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">

        <!-- Logo -->
        <h1 class="text-3xl font-semibold">
            <a href="/WS03/public/">Prosple</a>
        </h1>

        <!-- Navigation -->
        <nav class="flex items-center gap-4">

            <?php if(Session::has('user')) : ?>

                <span>
                    Welcome,
                    <?= htmlspecialchars(Session::get('user')['name']) ?>
                </span>

                <form method="POST" action="/auth/logout">
                    <button type="submit" class="text-white hover:underline">
                        Logout
                    </button>
                </form>

                <a
                    href="/WS03/public/listings/create"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded hover:shadow-md transition duration-300">

                    <i class="fa fa-edit"></i> Post a Job

                </a>

            <?php else: ?>

                <a href="/auth/login" class="text-white hover:underline">
                    Login
                </a>

                <a href="/auth/register" class="text-white hover:underline">
                    Register
                </a>

            <?php endif; ?>

        </nav>

    </div>
</header>
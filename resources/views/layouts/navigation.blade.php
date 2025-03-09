<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm p-3 fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">ISI BURGER 🍔</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex gap-3 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.catalogue') }}">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('client.commandes') }}">Mes Commandes</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link position-relative" href="#">
                        Panier 🛒
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary text-white" href="{{ route('login') }}">Se connecter</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Ajout de marge pour éviter que le contenu soit caché par la navbar fixe -->
<div class="container mt-5 pt-4">

</div>

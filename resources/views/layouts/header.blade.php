<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Lista de tareas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link  <?php echo $title == 'Tarea' ? 'active' : ''; ?>" aria-current="page" href="{{ url('/') }}">Tareas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $title == 'Lista' ? 'active' : ''; ?>" aria-current="page" href="{{ url('/lists') }}">Listas </a>
                </li>
            </ul>
            <div class="d-flex btn-group">
                <a href="#" class="btn btn-outline-secondary" target="_blank">GitHub APP</a>
                <a href="https://github.com/igmr/FlightPHP-Api-REST-TodoList" class="btn btn-outline-secondary" target="_blank">GitHub API</a>
            </div>
        </div>
    </div>
</nav>
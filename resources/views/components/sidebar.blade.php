<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}<sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gateway
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#personnelArea" aria-expanded="true" aria-controls="personnelArea">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Personnel Area</span>
        </a>
        <div id="personnelArea" class="collapse" aria-labelledby="headingPersonnelArea" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ route('users.index') }}" class="collapse-item">Users</a>
                <a href="#" class="collapse-item">Authorizations</a>
                <a href="#" class="collapse-item">Menu</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#publishings" aria-expanded="true" aria-controls="publishings">
            <i class="fas fa-fw fa-layer-group"></i>
            <span>Publishings</span>
        </a>
        <div id="publishings" class="collapse" aria-labelledby="headingPublishings" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ route('sites.index') }}" class="collapse-item">Site</a>
                <a href="{{ route('channels.index') }}" class="collapse-item">Channel</a>
                <a href="{{ route('visualinteractives.index') }}" class="collapse-item">Visual Interactive</a>
                <a href="#" class="collapse-item">RSS</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Contents
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#posts" aria-expanded="true" aria-controls="posts">
            <i class="fas fa-fw fa-pen-fancy"></i>
            <span>Posts</span>
        </a>
        <div id="posts" class="collapse" aria-labelledby="headingPosts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{route('posts.index')}}" class="collapse-item">Articles</a>
                <a href="#" class="collapse-item">Images</a>
                <a href="#" class="collapse-item">Videos</a>
                <a href="#" class="collapse-item">Recipes</a>
                <a href="#" class="collapse-item">Pricelists</a>
                <a href="#" class="collapse-item">Charts</a>
                <a href="#" class="collapse-item">Pollings</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#galleries" aria-expanded="true" aria-controls="galleries">
            <i class="fas fa-fw fa-pallet"></i>
            <span>Galleries</span>
        </a>
        <div id="galleries" class="collapse" aria-labelledby="headingGalleries" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ url('galleries/images') }}" class="collapse-item">Image</a>
                <a href="{{ url('galleries/videos') }}" class="collapse-item">Video</a>
                <a href="{{ url('galleries/podcasts') }}" class="collapse-item">Podcast</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Topics -->
    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('topics.index') }}">
            <i class="fas fa-fw fa-dolly"></i>
            <span>Topics</span></a>
    </li> -->

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#features" aria-expanded="true" aria-controls="features">
            <i class="fas fa-fw fa-flag-checkered"></i>
            <span>Features</span>
        </a>
        <div id="features" class="collapse" aria-labelledby="headingFeatures" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="#" class="collapse-item">Breakingnews</a>
                <a href="#" class="collapse-item">Push Notifications</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Ads Managaments
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventories" aria-expanded="true" aria-controls="inventories">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Inventories</span>
        </a>
        <div id="inventories" class="collapse" aria-labelledby="headingInventories" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="#" class="collapse-item">Ad Units</a>
                <a href="#" class="collapse-item">Key Values</a>
                <a href="#" class="collapse-item">Line Items</a>
                <a href="#" class="collapse-item">Creatives</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#deliveries" aria-expanded="true" aria-controls="deliveries">
            <i class="fas fa-fw fa-truck"></i>
            <span>Deliveries</span>
        </a>
        <div id="deliveries" class="collapse" aria-labelledby="headingDeliveries" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="#" class="collapse-item">Orders</a>
                <a href="#" class="collapse-item">Targets</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

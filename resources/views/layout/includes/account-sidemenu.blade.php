<div class="list-group">
    <a href="{{ route('quickDashBoard') }}" class="list-group-item list-group-item-action @if(isset($pageName) && $pageName == 'QuickDashboard') account-menu-list active @endif">
        <i class="fas fa-tachometer-alt"></i> Quick Dashboard
    </a>
    <a href="{{ route('myClients') }}" class="list-group-item list-group-item-action @if(isset($pageName) && $pageName == 'MyClients') account-menu-list active @endif">
        <i class="fas fa-user-tie"></i> Clients
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fas fa-toolbox"></i> Projects
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        <i class="fas fa-money-check-alt"></i> Payments
    </a>
</div>
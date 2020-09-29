<ul id="sidebarnav">
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#">
            <i class="fa fa-home"></i>
            <span class="hide-menu">Home</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-crop-square"></i>
            <span class="hide-menu">Stock Managment</span>
        </a>
        <ul aria-expanded="false" class="collapse  first-level">
            <li class="sidebar-item">
                <a href="{{ URL::to('/stock') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-left"></i>
                    <span class="hide-menu">Stock Overview </span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ URL::to('/stock/new/item') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-right"></i>
                    <span class="hide-menu"> Add solar panel(s) </span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ URL::to('/stock/new/solar/type') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-right"></i>
                    <span class="hide-menu"> New solar Type</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ URL::to('/stock/new/location') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-right"></i>
                    <span class="hide-menu"> Add Branch </span>
                </a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item">
        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-crop-square"></i>
            <span class="hide-menu">Clients Managment </span>
        </a>
        <ul aria-expanded="false" class="collapse  first-level">
            <li class="sidebar-item">
                <a href="{{ URL::to('/client') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-left"></i>
                    <span class="hide-menu"> Add client </span>
                </a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item">
        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-crop-square"></i>
            <span class="hide-menu">Payment Settings </span>
        </a>
        <ul aria-expanded="false" class="collapse  first-level">
            <li class="sidebar-item">
                <a href="{{ URL::to('/payment') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-left"></i>
                    <span class="hide-menu"> Payment Overview </span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ URL::to('/payment/charges') }}" class="sidebar-link">
                    <i class="mdi mdi-format-align-left"></i>
                    <span class="hide-menu"> Charges Fees </span>
                </a>
            </li>
        </ul>
    </li>
</ul>

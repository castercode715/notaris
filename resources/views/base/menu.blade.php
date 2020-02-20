@php
	use App\Utility;

	$uti = new Utility;
@endphp
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('images/employee/'.Auth::user()->photo) }}" style="height: 45px;" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->full_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
        <!-- sidebar menu: : style can be found in sidebar.less -->
          @if($uti->getUserNew() == NULL)

            <ul class="sidebar-menu" data-widget="tree">
              <li class="header">MAIN NAVIGATION</li>
              <li>
                <a href="/">
                  <i class="fa fa-home"></i> <span>Dashboard</span>
                  
                </a>
              </li>
            </ul>

          @else

          <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="/">
                  <i class="fa fa-home"></i> <span>Dashboard</span>
                  
                </a>
            </li>

            <li class="treeview" style="height: auto;">
              <a href="#">
                <i class="fa fa-th"></i> <span>Master</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu" style="display: none;">
                <li><a href="{{ route('document.index') }}"><i class="fa fa-circle-o"></i> <span>Document</span></a></li>
                
                <li>
                  <a href="{{ route('category.index') }}">
                    <i class="fa fa-circle-o"></i> <span>Category</span>
                  </a>
                </li>

                <li>
                  <a href="{{ route('notaris.index') }}">
                    <i class="fa fa-circle-o"></i> <span>Notaris</span>
                  </a>
                </li>


              </ul>
            </li>


            <li class="treeview" style="height: auto;">
              <a href="#">
                <i class="fa fa-gavel"></i> <span>Services</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu" style="display: none;">
                <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> Services List</a></li>
                
                
              </ul>
            </li>
            <li class="header">TRANSACTION</li>
            <li class="treeview" style="height: auto;">
              <a href="#">
                <i class="fa fa-cubes"></i> <span>Transaction</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu" style="display: none;">
                <li><a href="{{ route('fsk.index') }}"><i class="fa fa-circle-o"></i> FSK</a></li>
                <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> SO</a></li>
                <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> Invoice</a></li>
                
                
              </ul>
            </li>

            <li class="header">DOKUMEN</li>
            <li class="treeview" style="height: auto;">
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Salinan</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>

              <ul class="treeview-menu" style="display: none;">
                <!-- <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> FSK</a></li>
                <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> SO</a></li>
                <li><a href="{{ route('services.index') }}"><i class="fa fa-circle-o"></i> Invoice</a></li> -->
                
                
              </ul>
            </li>

            
        </ul>

        @endif
    </section>
<!-- /.sidebar -->
</aside>
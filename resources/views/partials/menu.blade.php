<ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
  <li class="nav-item">
    <a id="dashboardSideMenu" class=" nav-link" href="{{URL('dashboard')}}" role="button" aria-expanded="false">
      <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-home"></span></span><span class="nav-link-text ps-1">Dashboard</span>
      </div>
    </a>
    @if(isset($access[6]))
    <a id="deviceSideMenu" class="nav-link dropdown-indicator" href="#deviceCollapseMenu" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="deviceCollapseMenu">
      <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-plug"></span></span><span class="nav-link-text ps-1">Device</span>
      </div>
    </a>
    <ul class="nav collapse true" id="deviceCollapseMenu">
      @if(isset($access[7]))
      <li class="nav-item">
        <a id="deviceListSideMenu" class="nav-link" href="{{URL('/device')}}" aria-expanded="false">
          <div class="d-flex align-items-center"><span class="nav-link-text ps-1">List</span>
          </div>
        </a>
      </li>
      @endif
      @if(isset($access[6]))
      <li class="nav-item">
        <a id="recordsSideMenu" class="nav-link" href="{{URL('/records')}}" aria-expanded="false">
          <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Records</span>
          </div>
        </a>
      </li>
      @endif
    </ul>
    @endif
    @if(isset($access[5]))
    <a id="webHooksSideMenu" class="nav-link" href="{{URL('myApi')}}" role="button" aria-expanded="false">
      <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-assistive-listening-systems"></span></span><span class="nav-link-text ps-1">Webhooks</span>
      </div>
    </a>
    @endif
    @if(isset($access[1]))
    <a id="localizationSideMenu" class="nav-link dropdown-indicator" href="#localizationCollapseMenu" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="localizationCollapseMenu">
      <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-cogs"></span></span><span class="nav-link-text ps-1">Localization</span>
      </div>
    </a>
    <ul class="nav collapse true" id="localizationCollapseMenu">
      @if(isset($access[2]))
      <li class="nav-item">
        <a id="usersSideMenu" class="nav-link dropdown-indicator" href="#usersCollapseMenu" data-bs-toggle="collapse" aria-expanded="false" aria-controls="multi-level">
          <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Users</span>
          </div>
        </a>
        <!-- more inner pages-->
        <ul class="nav collapse false" id="usersCollapseMenu">
          @if(isset($access[3]))
          <li class="nav-item">
            <a id="userSideMenu" class="nav-link" href="{{URL('/user')}}" aria-expanded="false">
              <div class="d-flex align-items-center"><span class="nav-link-text ps-1">User</span>
              </div>
            </a>
            
            <!-- more inner pages-->
          </li>
          @endif
          @if(isset($access[4]))
          <li class="nav-item">
            <a id="userGroupSideMenu" class="nav-link" href="{{URL('/user_group')}}" aria-expanded="false">
              <div class="d-flex align-items-center"><span class="nav-link-text ps-1">User Group</span>
              </div>
            </a>
            <!-- more inner pages-->
          </li>
          @endif
        </ul>
      </li>
      @endif
    </ul>
    @endif
    <a class="nav-link" href="{{URL('logout')}}" role="button" aria-expanded="false">
      <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-power-off"></span></span><span class="nav-link-text ps-1">Logout</span>
      </div>
    </a>
  </li>
</ul>
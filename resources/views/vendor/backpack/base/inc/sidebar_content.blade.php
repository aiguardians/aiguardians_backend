<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<!-- Users, Roles Permissions -->
  <li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
      <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
      <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
    </ul>
  </li>

<li><a href="{{ backpack_url('speciality') }}"><i class="fa fa-dashboard"></i> <span>Specialities</span></a></li>
<li><a href="{{ backpack_url('group') }}"><i class="fa fa-dashboard"></i> <span>Groups</span></a></li>
<li><a href="{{ backpack_url('course') }}"><i class="fa fa-dashboard"></i> <span>Courses</span></a></li>
<li><a href="{{ backpack_url('task') }}"><i class="fa fa-dashboard"></i> <span>Tasks</span></a></li>
<li><a href="{{ backpack_url('student') }}"><i class="fa fa-dashboard"></i> <span>Students</span></a></li>
<li><a href="{{ backpack_url('teacher') }}"><i class="fa fa-dashboard"></i> <span>Teachers</span></a></li>
<li><a href="{{ backpack_url('schedule') }}"><i class="fa fa-dashboard"></i> <span>Schedule</span></a></li>

<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>

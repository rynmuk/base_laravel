@php
  use App\Utility;

  $uti = new Utility;
@endphp
<ul class="sidebar-menu" data-widget="tree">
  <li class="header">MAIN NAVIGATION</li>
  @php $uti->listMenu(); @endphp  

</ul>
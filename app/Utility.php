<?php
namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;

class Utility
{
	public function getUser($id)
    {
    	$fullname = '';
    	$model = DB::table('users')
                        ->where('id','=',$id)
                        ->first();
    	if($model)
    		$fullname = $model->name;

    	return $fullname;
    }

    public function localDate($date)
    {
        return date('d M Y H:i', strtotime($date)).' WIB';
    }

    public function listMenu()
    {
        $id = Auth::user()->role_id;

        $menus = '';
        // get menu level 1
        $parents = DB::table('sec_accessrole as a')
                ->join('sec_module as b','a.module_id','=','b.id')
                ->where('a.role_id',$id)
                ->where('b.parent_id', 0)
                ->select('a.module_id as id','b.module','b.link','b.icon')
                ->groupBy('a.module_id','b.module')
                ->orderBy('b.sort')
                ->get();
        // dd($id);
        // dd($parents);
        foreach ($parents as $first) 
        {
            $icon = $first->icon == '' ? 'fa fa-folder' : $first->icon;
            // cek if has child
            $childs = $this->getChild($first->id);
            $current = URL::current(); 
            
                    
            
            if(!$childs)
            {
                if(substr($current, 21 ) == $first->link){
                    $menus .= '<li class="active">
                        <a href="'.$first->link.'">
                            <i class="'.$icon.'"></i>
                            <span>'.$first->module.'</span>
                        </a>
                    </li>';
                }else if(substr($current, 21 ) == "" && $first->link == "/"){
                    $menus .= '<li class="active">
                        <a href="'.$first->link.'">
                            <i class="'.$icon.'"></i>
                            <span>'.$first->module.'</span>
                        </a>
                    </li>';
                }else{
                    $menus .= '<li>
                        <a href="'.$first->link.'">
                            <i class="'.$icon.'"></i>
                            <span>'.$first->module.'</span>
                        </a>
                    </li>';
                }
                
            }
            else
            {

                $menus .= '<li class="treeview">
                    <a href="#">
                        <i class="'.$icon.'"></i>
                        <span>'.$first->module.'</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>';
                $menus .= '<ul class="treeview-menu">';
                foreach ($childs as $second) {
                    $icon = $second->icon == '' ? 'fa fa-circle-o' : $second->icon;
                    $grandchilds = $this->getChild($second->id);
                    if(!$grandchilds)
                    {
                        if(substr($current, 21 ) == $second->link){
                        $menus .= '<li class="active">
                            <a href="'.$second->link.'">
                                <i class="'.$icon.'"></i>
                                <span>'.$second->module.'</span>
                            </a>
                        </li>';
                        }else{
                            $menus .= '<li >
                                <a href="'.$second->link.'">
                                    <i class="'.$icon.'"></i>
                                    <span>'.$second->module.'</span>
                                </a>
                            </li>';
                        }
                    }
                    else
                    {
                        $menus .= '<li class="treeview ">
                            <a href="#">
                                <i class="'.$icon.'"></i>
                                <span>'.$second->module.'</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>';
                        $menus .= '<ul class="treeview-menu">';
                        foreach($grandchilds as $third)
                        {
                            $icon = $third->icon == '' ? 'fa fa-circle-o' : $third->icon;
                            $menus .= '<li>
                                <a href="'.$third->link.'">
                                    <i class="'.$icon.'"></i>
                                    <span>'.$third->module.'</span>
                                </a>
                            </li>';
                        }
                        $menus .= '</ul></li>';
                    }
                }
                $menus .= '</ul></li>';
            }
        }

        echo $menus;
    }

    private function getChild($id) // parent_id
    {
        return DB::table('sec_module as a')
                ->join('sec_accessrole as b','a.id','=','b.module_id')
                ->where('a.parent_id', $id)
                ->where('a.active','1')
                ->where('a.is_deleted', '0')
                ->where('b.role_id', Auth::user()->role_id)
                ->select('a.id','a.module','a.parent_id','a.link','a.icon')
                ->groupBy('b.module_id','a.module')
                ->orderBy('a.sort')
                ->get()
                ->toArray();
    }

    public function isAllowedToUpdate($childTables = array(), $fk_ids = array(), $pk_id)
    {
        // looping pengecekan data di child table
        $no = 0;
        $total = 0;
        foreach($childTables as $table)
        {
            $count = DB::table($table)
                    ->where($fk_ids[$no], $pk_id)
                    ->where('is_deleted', '0')
                    ->count();
            $total = $total + $count;

        }

        return $total > 0 ? false : true;
    }

}
?>
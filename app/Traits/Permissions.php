<?php



namespace App\Traits;



use App\Models\Role;



trait Permissions {



    private function loadPermissions($roleId) {



        $subModulesCheck = function($subModule) {

            $subModule->orderBy('sort_id')->with('module');

        };

        // fetch role with permissions, sub modules and module

        $role = Role::with(['subModules' => $subModulesCheck])->find($roleId);
        $sidebar = [];

        if (sizeof($role->subModules) > 0) {

            // first loop over sub modules

            foreach ($role->subModules as $subModuleKey => $subModule) {

                $subModules = [];

                // collect sub modules of a module by ID and push in an array

                foreach ($role->subModules as $key => $sub) {

                    if ($subModule->module_id==$sub->module_id) {

                        $subModules[] = $sub->toArray();

                        $this->modules[$sub->controller] = [

                            'create' => $sub->pivot->mp_create,

                            'read' => $sub->pivot->mp_read,

                            'update' => $sub->pivot->mp_update,

                            'delete' => $sub->pivot->mp_delete,

                        ];

                        // unset current sub module to avoid duplication

                        unset($role->subModules[$key]);

                    }

                }

                // make sure you push the data in sidebar only if you find a sub module

                if (count($subModules) > 0) {

                    $sidebar[] = [

                        'id' => $subModule->module->id,

                        'title' => $subModule->module->title,

                        'icon_class' => $subModule->module->icon_class,

                        'subModules' => $subModules

                    ];

                }

            }

        }
        // put both arrays in session

        session()->put('SIDEBAR', $sidebar);

        session()->put('PERMISSIONS', $this->modules);

    }



}
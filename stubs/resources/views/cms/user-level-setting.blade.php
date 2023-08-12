@extends('cms.layouts.dashboard-admin')
@section('title', 'User Level Setting | ')
@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="#" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">User Levels</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Settings</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $name }} Settings</h1>
        </div>
    </div>
</div>
<div class="px-4 pt-6">
    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-2">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Menu
                </h3>
                <div>
                    @foreach ($dataMenu as $key => $menu)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <input id="menu{{ $key }}" type="checkbox" value="{{ $menu->code }}" data-child-class="menu-child-{{ $key }}" data-tipe="menu" data-level="parent" data-module-code="{{ $menu->module_code }}" class="setting w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $menu->check == 1 ? 'checked' : '' }}>
                            <label for="menu{{ $key }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $menu->name }}</label>
                        </div>
                        @if(count($menu->module_task) > 0)
                        <div class="ms-6 p-2 mb-2 border-2 rounded dark:border-gray-700">
                            <h6 class="mb-1 text-gray-900 dark:text-gray-300">Task</h6>
                            @foreach($menu->module_task as $key2 => $moduleTask)
                            <div class="flex items-center mb-1">
                                <input id="task{{ $key.$key2 }}" type="checkbox" value="{{ $moduleTask->code }}" data-tipe="module-task" data-level="single" class="setting w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $moduleTask->check == 1 ? 'checked' : '' }}>
                                <label for="task{{ $key.$key2 }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $moduleTask->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @if (count($menu->child) > 0)
                        <div class="ms-6 mt-2">
                            @foreach ($menu->child as $key2 => $menuChild)
                            <div class="flex items-center mb-2">
                                <input id="menuChild{{ $key.$key2 }}" type="checkbox" value="{{ $menuChild->code }}"  data-parent-id="menu{{ $key }}" data-tipe="menu" data-level="child" data-module-code="{{ $menuChild->module_code }}" class="setting menu-child-{{ $key }} w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $menuChild->check == 1 ? 'checked' : '' }}>
                                <label for="menuChild{{ $key.$key2 }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $menuChild->name }}</label>
                            </div>
                            @if(count($menuChild->module_task) > 0)
                            <div class="ms-6 p-2 mb-2 border-2 rounded dark:border-gray-700">
                                <h6 class="mb-1 text-gray-900 dark:text-gray-300">Task</h6>
                                @foreach($menuChild->module_task as $key3 => $moduleTask)
                                <div class="flex items-center mb-1">
                                    <input id="task{{ $key.$key2.$key3 }}" type="checkbox" value="{{ $moduleTask->code }}"  data-tipe="module-task" data-level="single" class="setting w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $moduleTask->check == 1 ? 'checked' : '' }}>
                                    <label for="task{{ $key.$key2.$key3 }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $moduleTask->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    General Access
                </h3>
                <div>
                    @foreach ($dataModule as $key => $module)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <input id="module{{ $key }}" type="checkbox" value="{{ $key }}" data-child-class="module-task-child-{{ $key }}" data-tipe="module-task" data-level="parent" class="setting w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $module->check == 1 ? 'checked' : '' }}>
                            <label for="module{{ $key }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $module->name }}</label>
                        </div>
                        @if (count($module->task) > 0)
                        <div class="ms-6 mt-2">
                            @foreach ($module->task as $key2 => $task)
                            <div class="flex items-center mb-2">
                                <input id="moduleTask{{ $key.$key2 }}" type="checkbox" value="{{ $task->code }}"  data-parent-id="module{{ $key }}" data-tipe="module-task" data-level="child" class="setting module-task-child-{{ $key }} w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $task->check == 1 ? 'checked' : '' }}>
                                <label for="moduleTask{{ $key.$key2 }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $task->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(".setting").on('click', function() {
        let checkbox    = $(this);
        let code        = checkbox.val();
        let codes       = [];
        let moduleCode  = "";
        let moduleCodes = [];
        let tipe        = checkbox.data('tipe');
        let level       = checkbox.data('level');
        let checked     = checkbox[0].checked;

        let childClass  = "";
        let parentId    = "";
        let parentChecked = false;

        if (level == 'parent') {
            childClass = checkbox.data('child-class');

            $("." + childClass).each(function() {
                codes.push($(this).val());

                if (tipe == 'menu') {
                    moduleCodes.push($(this).data('module-code'))
                }
            })
        } else if (level == 'child') {
            parentId = checkbox.data('parent-id');
            childClass = $("#" + parentId).data('child-class');

            let allChild = $("." + childClass).length;
            let allChildChecked = $("." + childClass + ":checked").length;

            if (allChild == allChildChecked && checked) {
                parentChecked = true;
            }
        }

        if (codes.length <= 0) {
            codes.push(code);
        }

        if (tipe == 'menu') {
            moduleCode = checkbox.data('module-code')
            if (moduleCodes.length <= 0) {
                moduleCodes.push(moduleCode);
            }
        }

        loading(true);
        const data = {
            _token: '{{ csrf_token() }}',
            tipe: tipe,
            codes: codes,
            module_codes: moduleCodes,
            checked: checked ? 1 : 0
        }
        
        $.ajax({
            type: 'POST',
            url: '/cms/user-level/{{ $id }}/setting',
            data: data,
            success: (res) => {
                loading(false);

                if (res.status != 'OK') {
                    alert("Gagal!", "Terjadi kesalahan internal!", "error")
                    return false
                }

                checkbox.prop('checked', checked);

                if (level == 'parent') {
                    $("." + childClass).prop('checked', checked);
                } else if (level == 'child') {
                    $("#" + parentId).prop('checked', parentChecked);
                }
            },
            error: (res) => {
                loading(false);
                alert("Task Tidak Ada!", "Terjadi kesalahan internal! Silahkan coba lagi", "error")

                return false
            }
        })

        return false;
    })
</script>
@endsection
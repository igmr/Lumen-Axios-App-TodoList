<x-master title="Tarea">

    <x-slot name="styles">
        <style>

            .input-task {
                color: white;
                background-color: #212529;
            }

            .select-list {
                color: white;
                background-color: #212529;
            }

        </style>
    </x-slot>
    
    <!-- Toast -->
    <div class="toast-container top-0 start-50 translate-middle-x p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header text-white">
                <strong class="me-auto">
                    <i class="fa-solid fa-exclamation"></i>
                    &nbsp;<span class='toast-title'></span> 
                </strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
            </div>
        </div>
    </div>
    <!-- End Toast -->

    <!-- Title -->
    <div class="d-flex justify-content-between align-items-center">
        <h5>
            <i class="fa-solid fa-list-check"></i>
            &nbsp;Tareas
        </h5>
        <!--
        <div class="btn-group btn-show-list">
            <a href="not-completed" class="btn btn-outline-info"><i class="fa-regular fa-square"></i></a>
            <a href="completed" class="btn btn-outline-success"><i class="fa-solid fa-square-check"></i></a>
            <a href="all" class="btn btn-outline-primary"><i class="fa-solid fa-list-check"></i></a>
        </div>
        -->
    </div>
    <!-- End Title -->

    <!-- Breadcrumb -->
    <nav style="--bs-breadcrumb-divider: '~>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">
                    <i class="fa-solid fa-house"></i>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fa-solid fa-list-check"></i>
                &nbsp;Tarea
            </li>
        </ol>
    </nav>
    <!-- End Breadcrumb -->

    <!-- Form -->
    <form id="formTask" class="d-flex flex-column m-0">
        <div class="input-group input-group-sm mb-3">
            <label class="input-group-text" for="select-list">
                <i class="fa-solid fa-bars-staggered"></i>
            </label>
            <select class="form-select select-list" id="select-list" name="list">
            </select>
        </div>
        <div class="input-group input-group-sm mb-3">
            <label class="input-group-text" for="input-task">
                <i class="fa-solid fa-list-check"></i>
            </label>
            <input type="text" class="form-control input-task" name="title" placeholder="Nueva tarea" id="input-task">
            <button class="btn btn-outline-success" type="submit">
                <i class="fa-regular fa-square-plus"></i>
                &nbsp;Guardar
            </button>
        </div>
    </form>
    <!-- End Form -->

    <!-- Card -->
    <div class="card border-info shadow">
        <div class="card-body text-center h-100 d-none" id="not-data">
            <h1 class="card-title text-warning">No hay tareas pendiente</h1>
        </div>
        <div class="list-group list-group-flush"  id="task">
        </div>
    </div>
    <!-- End Card -->

    <x-slot name="script">
        <script>
            const $formTask     = document.querySelector('#formTask');
            const $title        = $formTask.title;
            const $list         = $formTask.list;
            const $selectList   = document.querySelector('#select-list');
            const $btnShowList  = document.querySelector('.btn-show-list');

            const $toast        = document.querySelector('#toast');
            const $toastTitle   = document.querySelector('.toast-title'); 
            const $toastBody    = document.querySelector('.toast-body'); 

            const $divTask      = document.querySelector('#task');

            const $urlList      = 'https://ivangabino.com/apis/FlightPHP-Api-REST-TodoList/api/v1/list';
            const $url          = 'https://ivangabino.com/apis/FlightPHP-Api-REST-TodoList/api/v1/task';

            function delay(time) {
                return new Promise(resolve => setTimeout(resolve, time));
            }

            const placeholder = ()=>
            {
                return `
                <div class="list-group-item d-flex justify-content-between" aria-hidden="false">
                    <div class="d-flex flex-column me-1 flex-fill placeholder-glow">
                        <label class="lh-sm form-check-label fs-4 placeholder"></label>
                        <div>
                            <small class="badge bg-info text-info placeholder col-6">tmp</small>
                            <small class="badge bg-warning text-warning placeholder col-6">tmp</small>
                        </div>
                    </div>
                    <div class="me-3 d-flex justify-content-center align-items-center flex-shrink-1 placeholder-glow">
                        <a href="#" tabindex="-1" class="btn btn-danger disabled placeholder col-4" aria-hidden="true"></a>
                        <a href="#" tabindex="-1" class="btn btn-success disabled placeholder col-4" aria-hidden="true"></a>
                    </div>
                </div>`;
            }

            const task = (id, title, list, date, status='Pendiente')=>
            {
                return `
                    <div class="list-group-item d-flex justify-content-between">
                        <div class="me-2 d-flex flex-column">
                            <div class="me-2 d-flex">
                                <!--
                                <div>
                                    <span class="badge text-bg-info">${status}</span>
                                </div>
                                -->
                                &nbsp;<span class="fs-5 text-white">${title}</span>
                            </div>
                            <div>
                                <span class="badge text-bg-primary">${list}</span>
                            </div>
                            <div>
                                <span class="badge text-bg-warning">${date}</span>
                            </div>
                        </div>
                        <div class=" d-flex justify-content-center align-items-center btn-group"">
                            <a href="${id}" class="btn btn-outline-danger remove">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                            <a href="${id}" class="btn btn-outline-success complete">
                                <i class="fa-regular fa-circle-check"></i>
                            </a>
                        </div>
                    </div>`;
            }

            const fillPlaceholders = (num = 10)=>
            {
                const divItem = document.createElement('div');
                let items = '';
                for(i = 0; i < num ; i++)
                {
                    items = items + placeholder();
                }
                divItem.innerHTML = items;
                $divTask.innerHTML = '';
                $divTask.append(divItem);
            }
            const fillListTask =  async () =>
            {
                document.querySelector('#not-data').classList.add('d-none');
                const data = await getTasks();
                //console.log(data);
                const divItem = document.createElement('div');
                console.log(data.length)
                if(data.length == 0)
                {
                    await delay(1000);
                    $divTask.innerHTML = '';
                    document.querySelector('#not-data').classList.remove('d-none');
                    return;
                }
                let items = '';
                data.every(item =>
                {
                    items = items + task(item.id, item.title, item.list, item.created, item.complete);
                    return true;
                });
                divItem.innerHTML = items;
                await delay(1000);
                $divTask.innerHTML = '';
                $divTask.append(divItem);
            }
            const fillSelectList = async ()=>
            {
                const data = await getLists();
                data.forEach((item, index) =>{
                    const option = document.createElement('option')
                    option.value = item.id;
                    option.text = item.name;
                    if(item.id == 1)
                    {
                        option.setAttribute('selected', 'true');
                    }
                    $selectList.appendChild(option);
                });
            }

            const handleSubmit = async (e)=>
            {
                e.preventDefault();
                const title   = $title.value ? $title.value  : '';
                const list    = $list.value ?  $list .value  : 1;
                const payload = {title, list};
                await postTask(payload);
            }
            const handleShowList = async (e)=>
            {
                e.preventDefault();
                console.log('list');
            }
            const handleToast = (description = 'Error no controlado', action = 'error')=>
            {
                const toast = new bootstrap.Toast($toast);
                const bgColor = (action != 'error' ? 'bg-success' : 'bg-danger');
                const title = (action != 'error' ? 'Exito' : 'Error')
                $toast.classList.remove('bg-success');
                $toast.classList.remove('bg-danger');
                $toast.classList.add(bgColor);
                $toastBody.innerHTML = description;
                $toastTitle.innerHTML = title;
                toast.show();
            }
            const handleDeleteTask = async (id)=>
            {
                const status = await deleteTask(id);
                if(status ==  200)
                {
                    handleToast('Tarea eliminada', 'success');
                    handleLoader();
                    return;
                }
                handleToast('No se puede eliminar la tarea');
                return;
            }
            const handleTask = async (e)=>
            {
                e.preventDefault();
                let id = 0;
                let a = '';
                let existe = false;
                if(e.target && e.target.tagName === "A")
                {
                    a = e.target
                    href = a.href;
                    url = href.split('/')
                    id = url[url.length -1]
                    existe = true;
                }
                if(e.target && e.target.tagName === "I")
                {
                    a = e.target.parentNode;
                    href = a.href; 
                    url = href.split('/')
                    id = url[url.length -1]
                    existe = true;
                }
                if(existe)
                {
                    if(a.classList.contains('remove'))
                    {
                        await handleDeleteTask(id);
                        return;
                    }
                    if(a.classList.contains('complete'))
                    {
                        await postCompleteTask(id);
                    }
                }
            }


            const postTask = async (payload)=>
            {
                try {
                    const options = {
                        'Content-Type': 'application/json'
                    }
                    const {data, status, headers} = await axios.post($url, payload, options);
                    if(status == 201)
                    {
                        $title.value = '';
                        handleToast(data.message, 'success');
                        handleLoader();
                        return;
                    }
                   
                } catch ($ex) {
                    console.log($ex);
                    const {data, status} = $ex.response;
                    const {error} = data;
                    handleToast(`<strong>Tarea</strong>: ${error.general}`)
                    return;
                }
            }
            const postCompleteTask = async (id)=>
            {
                try {
                    const options = {
                        'Content-Type': 'application/json'
                    }
                    const {data, status, headers} = await axios.post(`${$url}/completed/${id}`, {}, options);
                    if(status == 200)
                    {
                        $title.value = '';
                        handleToast('Tarea completada', 'success');
                        handleLoader();
                        return;
                    }
                   
                } catch ($ex) {
                    
                    handleToast(`<strong>Tarea</strong>: No se pudo completar`)
                    return;
                }
            }
            const getTasks = async ()=>
            {
                try
                {
                    const {data, status, headers} = await axios.get($url);
                    return data;
                } catch ($ex)
                {
                    return [];
                }
            }
            const getTasksAll = async ()=>
            {
                try
                {
                    const {data, status, headers} = await axios.get(`${$url}/all`);
                    return data;
                } catch ($ex)
                {
                    return [];
                }
            }
            const getTasksCompleted = async ()=>
            {
                try
                {
                    const {data, status, headers} = await axios.get(`${$url}/completed`);
                    return data;
                } catch ($ex)
                {
                    return [];
                }
            }
            const getLists = async ()=>
            {
                try
                {
                    const {data, status, headers} = await axios.get($urlList);
                    return data;
                } catch ($ex)
                {
                    return [];
                }
            }
            const deleteTask = async (id)=>
            {
                try
                {
                    const {data, status, headers} = await axios.delete(`${$url}/${id}`);
                    return status;
                }catch($ex)
                {
                    const {data, status} = $ex.response;
                   return status;
                }
            }

            const handleLoader = async()=>
            {
                fillPlaceholders();
                await fillListTask();
            }
            (async ()=>
            {
                await fillSelectList();
                await handleLoader();
            })()
            //$btnShowList.addEventListener('click'  , handleShowList)
            $formTask.addEventListener('submit'    , handleSubmit)
            $divTask.addEventListener('click'      , handleTask)
        </script>
    </x-slot>
</x-master>


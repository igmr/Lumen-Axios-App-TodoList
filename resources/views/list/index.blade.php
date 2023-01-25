<x-master title="Lista">
    <x-slot name="styles">
        <style>
            .txtName {
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
    <div class="d-flex">
        <h5>
            <i class="fa-solid fa-bars-staggered"></i>
            &nbsp;Listas
        </h5>
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
                <i class="fa-solid fa-bars-staggered"></i>
                &nbsp;Lista
            </li>
        </ol>
    </nav>
    <!-- End Breadcrumb -->

    <!-- Form -->
    <form id="formList">
        <div class="input-group input-group-sm mb-3">
            <label class="input-group-text" for="txtName">
                <i class="fa-solid fa-bars-staggered"></i>
            </label>
            <input type="text" class="form-control txtName" name="name" placeholder="Nueva lista" id="txtName">
            <button class="btn btn-outline-success" type="submit">
                <i class="fa-regular fa-square-plus"></i>
                &nbsp;Guardar
            </button>
        </div>       
    </form>
    <!-- End Form -->

    <!-- Card -->
    <div class="card border-info shadow">
        <div class="list-group list-group-flush"  id="list">
        </div>
    </div>
    <!-- End Card -->

    <!-- End Form -->
    <x-slot name="script">
        <script>
            const $formList   = document.querySelector('#formList');
            const $name       = $formList.name;

            const $toast      = document.querySelector('#toast');
            const $toastTitle = document.querySelector('.toast-title'); 
            const $toastBody  = document.querySelector('.toast-body'); 

            const $divList    = document.querySelector('#list');
            const $url        = 'https://ivangabino.com/apis/FlightPHP-Api-REST-TodoList/api/v1/list';

            function delay(time) {
                return new Promise(resolve => setTimeout(resolve, time));
            }

            const placeholder = ()=>
            {
                return `<div class="list-group-item d-flex justify-content-between placeholder-glow">
                            <h5 class="placeholder col-6">title</h5>
                            <a href="#" tabindex="-1" class="btn btn-danger disabled placeholder col-1" aria-hidden="true"></a>
                        </div>`;
            }
            const list = (id, title)=>
            {
                return `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <h5 class="font-monospace">${title}</h5>
                        <a class="btn btn-outline-danger delete" href="${id}" ><i class="fa-regular fa-trash-can"></i></a>
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
                $divList.innerHTML = '';
                $divList.append(divItem);
            }

            const fillList =  async () =>
            {
                const data = await getLists();
                const divItem = document.createElement('div');
                let items = '';
                data.every(item =>
                {
                    items = items + list(item.id, item.name);
                    return true;
                });
                divItem.innerHTML = items;
                await delay(1000);
                $divList.innerHTML = '';
                $divList.append(divItem);
            }

            const handleSubmit = async (e)=>
            {
                e.preventDefault();
                console.log('click')
                const name = $name.value ? $name.value : null;
                if(name == null)
                {
                    handleToast('Nombre de lista es requerido.');
                    return;
                }
                const payload = { name };
                await postList(payload);
                return;
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
            const handleList = async (e)=>
            {
                e.preventDefault();
                let id = 0;
                if(e.target && e.target.tagName === 'A' )
                {
                    href = e.target.href;
                    url = href.split('/')
                    id = url[url.length -1]
                }
                if(e.target && e.target.tagName === 'I' )
                {
                    const a = e.target.parentNode;
                    href = a.href; 
                    url = href.split('/')
                    id = url[url.length -1]
                }
                await handleDeleteList(id);
            }
            const handleDeleteList = async (id)=>
            {
                const status = await deleteList(id);
                if(status ==  200)
                {
                    handleToast('Lista eliminada.', 'success');
                    handleLoader();
                    return;
                }
                handleToast('No se pudo eliminar la lista');
                return;
            }
            const postList = async (payload)=>
            {
                try {
                    const options = {
                        'Content-Type': 'application/json'
                    }
                    const {data, status, headers} = await axios.post($url, payload, options);
                    if(status == 201)
                    {
                        $name.value = '';
                        handleToast(data.message, 'success');
                        handleLoader();
                        return;
                    }
                   
                } catch ($ex) {
                    const {data, status} = $ex.response;
                    const {error} = data;
                    
                    handleToast(`<strong>Lista</strong>: ${error.name}`)
                    return;
                }
            }
            const getLists = async ()=>
            {
                try
                {
                    const {data, status, headers} = await axios.get($url);
                    return data;
                }catch($ex)
                {
                   return [];
                }
            }
            const deleteList = async (id)=>
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
            const handleLoader = ()=>
            {
                fillPlaceholders();
                fillList();
            }
            $formList.addEventListener('submit', handleSubmit);
            $divList.addEventListener('click', handleList);
            (()=>
            {
                handleLoader();
            })()
        </script>
    </x-slot>
</x-master>
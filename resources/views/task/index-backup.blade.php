<x-master title="Tarea">

    <div class="input-group pb-3">
        <span class="input-group-text text-white">
            <i class="fa-solid fa-list-check"></i> &nbsp; Lista de tareas
        </span>
        <select class="form-select" id="filtro" aria-label="Filtro"
            style="background-color: #212529; color:white;">
        <option value="0">Tareas no completadas</option>
        <option value="1">Tareas completadas</option>
        <option value="2">Tareas eliminadas</option>
        <option value="3">Todas las tareas</option>
        </select>
        <button class="btn btn-outline-success" type="button">
            <i class="fa-regular fa-square-plus"></i>
            Nueva tarea
        </button>
    </div>
    

    <div class="card border-info shadow">
        <div class="card-body" id="list-task">
        </div>
        <div class="card-footer border-info text-center align-bottom">
            <p class="text-danger visually-hidden" id="ver-todo-hidden">Ver Todos</p>
            <a class="link-info" id="load-tasks" >Ver todos</a>
        </div>
    </div>

    <x-slot name="script">
        <script>
            //
            const $listTask    = document.querySelector('#list-task');
            const $btnLoadTaks = document.querySelector('#load-tasks');
            let   $loadAll     = false;
            //
            const $placeholder = ()=>
            {
                return `
                <div class="list-group-item d-flex justify-content-between" aria-hidden="false">
                    <div class="me-3 d-flex justify-content-center align-items-center flex-shrink-1 placeholder-glow">
                        <input class="form-check-input placeholder" type="checkbox" value="">
                    </div>
                    <div class="d-flex flex-column me-1 flex-fill placeholder-glow">
                        <label class="lh-sm form-check-label fs-4 placeholder"></label>
                        <div>
                            <small class="badge bg-info text-info placeholder col-6">tmp</small>
                        </div>
                    </div>
                    <div class="me-3 d-flex justify-content-center align-items-center flex-shrink-1 placeholder-glow">
                        <a href="#" tabindex="-1" class="btn btn-info disabled placeholder col-4" aria-hidden="true"></a>
                    </div>
                </div>`;
            }
            const $task = (id, title, description, list, status, created) =>
            {
                /*
                console.info(id)
                console.info(title)
                console.info(description)
                console.info(status)
                */
                const color       = (status == 0 ? 'info' : 'success');
                const icon        = (status == 0 ? 'info' : 'check');
                const disable     = (status == 0 ? '' : 'checked disabled');
                const disabledBtn = (status == 0 ? '' : 'disabled');
                const time        = new Date(created).toLocaleTimeString();
                const date        = new Date(created).toLocaleDateString('es-ES', {  year: 'numeric', month: 'short', day: '2-digit' })
                return `
                    <div class="me-3 d-flex justify-content-center align-items-center flex-shrink-1">
                        <input class="form-check-input" type="checkbox" value="" id="ckb-${id}" ${disable}>
                    </div>
                    <div class="d-flex flex-column me-1 flex-fill">
                        <label class="lh-sm form-check-label fs-4 text-truncate" for="ckb-${id}" ${disable}>${title}</label>
                        <div>
                            <small class="badge text-bg-${color} flex-shrink-1">${time} - ${date}</small>
                        </div>
                    </div>
                    <div class="me-3 d-flex justify-content-center align-items-center flex-shrink-1">
                        <button class="btn btn-outline-${color} rounded" ${disabledBtn} ><i class="fa-solid fa-${icon} fa-xl"></i></button>
                    </div>`
            }
            //* 
            const fillPlaceholder = ()=>
            {
                $listTask.innerHTML = '';
                $id = 0;
                const tasks = document.createElement('div');
                tasks.className = "list-group list-group-flush";
                do{
                    const item = document.createElement('div');
                    item.innerHTML = $placeholder();
                    tasks.append(item);
                    $id++;
                }while($id < 10)
                $listTask.append(tasks)
            }
            const fillListTask = async()=>
            {
                const tasks = await getTasksNotCompleted();
                console.info(tasks)
                const div = document.createElement('div');
                div.className = "list-group list-group-flush";
                tasks.every(({id,title, description, list, status, created}, index) =>{
                    const item = document.createElement('div')
                    item.setAttribute("id","task-"+id);
                    item.className = "list-group-item d-flex justify-content-between";
                    item.innerHTML = $task(id,title, description, list, status, created);
                    div.append(item);
                    if(!$loadAll)
                    {
                        if(index == 10)
                        {
                            return false;
                        }
                    }
                    return true;
                });
                //await delay(3000);
                $listTask.innerHTML = '';
                $listTask.append(div)
            }
            const getTasksNotCompleted = async ()=>
            {
                try
                {
                    const url = 'https://ivangabino.com/apis/FlightPHP-Api-REST-TodoList/api/v1/task';
                    const {data} = await axios.get(url);
                    return data;
                }catch($ex)
                {
                    console.log($ex.response.data);
                    console.log($ex.response.status);
                    return [];
                }
            }
            function delay(time) {
                return new Promise(resolve => setTimeout(resolve, time));
            }
            $btnLoadTaks.addEventListener('click', async (e)=>
            {
                $btnLoadTaks.classList.add('visually-hidden');
                document.querySelector("#ver-todo-hidden").classList.remove('visually-hidden');
                e.preventDefault();
                $loadAll = true;
                fillPlaceholder();
                await fillListTask();
            });
            (async ()=>
            {
                fillPlaceholder();
                fillListTask();
            })()
        </script>
    </x-slot>
</x-master>


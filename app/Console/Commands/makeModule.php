<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {module} {--controller=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a CRUD module';

    protected $disk;

    protected $constantDirectory = 'Constants';
    protected $constantNameSuffix = 'Constant';

    protected $controllerDirectory = 'Http/Controllers/Api/V1';
    protected $controllerNameSuffix = 'Controller';

    protected $indexRequestNameSuffix = 'IndexRequest';
    protected $storeRequestNameSuffix = 'StoreRequest';
    protected $updateRequestNameSuffix = 'UpdateRequest';

    protected $modelDirectory = 'Models';
    protected $modelNameSuffix = '';

    protected $policyDirectory = 'Policies';
    protected $policyNameSuffix = 'Policy';

    protected $repositoryDirectory = 'Repositories';
    protected $repositoryNameSuffix = 'Repository';

    protected $resourceDirectory = 'Resources';
    protected $resourceNameSuffix = 'Resource';

    protected $serviceDirectory = 'Services';
    protected $serviceNameSuffix = 'Service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::createLocalDriver(['root' => app_path()]);
    }

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $moduleName = $this->getModuleName();

        $constantDirectory = $this->getConstantDirectory();
        $constantName = $this->getConstantName();

        $controllerDirectory = $this->getControllerDirectory();
        $controllerName = $this->getControllerName();

        $requestDirectory = $this->getRequestDirectory();

        $modelDirectory = $this->getModelDirectory();
        $modelName = $this->getModelName();

        $policyDirectory = $this->getPolicyDirectory();
        $policyName = $this->getPolicyName();

        $repositoryDirectory = $this->getRepositoryDirectory();
        $repositoryName = $this->getRepositoryName();

        $resourceDirectory = $this->getResourceDirectory();
        $resourceName = $this->getResourceName();

        $serviceDirectory = $this->getServiceDirectory();
        $serviceName = $this->getServiceName();

        try {
            if (empty($this->disk->exists($constantDirectory . DIRECTORY_SEPARATOR . $constantName . '.php'))) {
                $this->makeConstant($constantDirectory, $constantName);
                $this->info('Constant');
            }

            if (empty($this->disk->exists($controllerDirectory . DIRECTORY_SEPARATOR . $controllerName . '.php'))) {
                $this->makeController($controllerDirectory, $controllerName);
                $this->info('Controller');
            }

            if (empty($this->disk->exists($requestDirectory))) {
                $this->makeRequest($requestDirectory);
                $this->info('Request');
            }

            if (empty($this->disk->exists($modelDirectory . DIRECTORY_SEPARATOR . $modelName . '.php'))) {
                $this->makeModel($modelDirectory, $modelName);
                $this->info('Model');
            }

            if (empty($this->disk->exists($policyDirectory . DIRECTORY_SEPARATOR . $policyName . '.php'))) {
                $this->makePolicy($policyDirectory, $policyName);
                $this->info('Policy');
            }

            if (empty($this->disk->exists($repositoryDirectory . DIRECTORY_SEPARATOR . $repositoryName . '.php'))) {
                $this->makeRepository($repositoryDirectory, $repositoryName);
                $this->info('Repository');
            }

            if (empty($this->disk->exists($resourceDirectory . DIRECTORY_SEPARATOR . $resourceName . '.php'))) {
                $this->makeResource($resourceDirectory, $resourceName);
                $this->info('Resource');
            }

            if (empty($this->disk->exists($serviceDirectory . DIRECTORY_SEPARATOR . $serviceName . '.php'))) {
                $this->makeService($serviceDirectory, $serviceName);
                $this->info('Service');
            }

            $this->info('Module ' . $moduleName . ' created successfully!');
        } catch (\Exception $e) {
            // $this->disk->deleteDirectory($modulePath);
            $this->error('Failed to create module');
            $this->line($e->getMessage());
        }
    }

    /**
     * Make Constant
     * @param $constantDirectory
     * @param $constantName
     */
    public function makeConstant($constantDirectory, $constantName)
    {
        $content = <<<XML
        <?php   

        namespace App\\$constantDirectory;

        class $constantName
        {
            //
        }
        XML;

        $this->disk->put($constantDirectory . DIRECTORY_SEPARATOR . $constantName . '.php', $content);
    }

    /**
     * Make Controller
     * @param $controllerDirectory
     * @param $controllerName
     */
    public function makeController($controllerDirectory, $controllerName)
    {
        $optionPath = $this->getOptionPath();
        $moduleName = $this->getModuleName();
        $serviceName = $this->getServiceName();
        $serviceNameCamelCase = Str::camel($serviceName);
        $indexRequestName = $this->getIndexRequestName();
        $storeRequestName = $this->getStoreRequestName();
        $updateRequestName = $this->getUpdateRequestName();
        $indexRequestPath = $this->getIndexRequestPath();
        $storeRequestPath = $this->getStoreRequestPath();
        $updateRequestPath = $this->getUpdateRequestPath();
        $resourceName = $this->getResourceName();

        $content = <<<XML
        <?php

        namespace App\Http\Controllers\Api\V1\\$optionPath;

        use App\Http\Controllers\Controller;
        use Exception;
        use Illuminate\Support\Facades\Log;
        use Illuminate\Http\JsonResponse;
        use App\Exceptions\Code;
        use App\Exceptions\Message;
        use App\Services\\$serviceName;
        use $indexRequestPath;
        use $storeRequestPath;
        use $updateRequestPath;
        use App\Resources\\$resourceName;
        use App\Models\\$moduleName;

        class $controllerName extends Controller
        {
            protected $serviceName \$$serviceNameCamelCase;

            /**
             * Constructor
             * @param $serviceName \$$serviceNameCamelCase
             */
            public function __construct($serviceName \$$serviceNameCamelCase)
            {
                \$this->$serviceNameCamelCase = \$$serviceNameCamelCase;
                \$this->middleware(config('middleware.admin'));
            }
            
            /**
             * List all records
             * @param $indexRequestName \$request
             * @return JsonResponse
             */
            public function index($indexRequestName \$request): JsonResponse
            {
                try {
                    \$this->authorize('viewAny', $moduleName::class);
                    
                    \$params = \$request->validated();
                    \$result = \$this->{$serviceNameCamelCase}->lists(\$params);
                    \$response = new $resourceName(\$result);
                    return \$this->sendResponse(\$response->index());
                } catch (Exception \$e) {
                    Log::error(\$e->getMessage());
                    return \$this->sendError(Code::FAILED, \$e->getMessage());
                }
            }

            /**
             * Create new record
             * @param $storeRequestName \$request
             * @return JsonResponse
             */
            public function store($storeRequestName \$request): JsonResponse
            {
                try {
                    \$this->authorize('create', $moduleName::class);

                    \$params = \$request->validated();
                    \$result = \$this->{$serviceNameCamelCase}->create(\$params);
                    \$response = new $resourceName(\$result);
                    return \$this->sendResponse(\$response->store());
                } catch (Exception \$e) {
                    Log::error(\$e->getMessage());
                    return \$this->sendError(Code::FAILED, \$e->getMessage());
                }
            }

            /**
             * Get detail record
             * @param int|string \$id
             * @return JsonResponse
             */
            public function show(int|string \$id): JsonResponse
            {
                try {
                    \$this->authorize('view', $moduleName::class);

                    \$result = \$this->{$serviceNameCamelCase}->show(\$id);
                    if (empty(\$result)) {
                        return \$this->sendError(CODE::NOT_FOUND, Message::FAILED);
                    }
                    \$response = new $resourceName(\$result);
                    return \$this->sendResponse(\$response->show());
                } catch (Exception \$e) {
                    Log::error(\$e->getMessage());
                    return \$this->sendError(Code::FAILED, \$e->getMessage());
                }
            }

            /**
             * Update record
             * @param $updateRequestName \$request
             * @param int|string \$id
             * @return JsonResponse
             */
            public function update($updateRequestName \$request, int|string \$id): JsonResponse
            {
                try {
                    \$this->authorize('update', $moduleName::class);

                    \$params = \$request->validated();
                    \$result = \$this->{$serviceNameCamelCase}->update(\$id, \$params);
                    if (empty(\$result)) {
                        return \$this->sendError(CODE::NOT_FOUND, Message::FAILED);
                    }
                    \$response = new $resourceName(\$result);
                    return \$this->sendResponse(\$response->store());
                } catch (Exception \$e) {
                    Log::error(\$e->getMessage());
                    return \$this->sendError(Code::FAILED, \$e->getMessage());
                }
            }

            /**
             * Delete record
             * @param int|string \$id
             * @return JsonResponse
             */
            public function destroy(int|string \$id): JsonResponse
            {
                try {
                    \$this->authorize('delete', $moduleName::class);

                    \$result = \$this->{$serviceNameCamelCase}->delete(\$id);
                    if (empty(\$result)) {
                        return \$this->sendError(CODE::NOT_FOUND, Message::FAILED);
                    }
                    return \$this->sendResponse([\$result]);
                } catch (Exception \$e) {
                    Log::error(\$e->getMessage());
                    return \$this->sendError(Code::FAILED, \$e->getMessage());
                }
            }
        }
        XML;

        $this->disk->put($controllerDirectory . DIRECTORY_SEPARATOR . $controllerName . '.php', $content);
    }

    /**
     * Make Request
     * @param $requestDirectory
     */
    public function makeRequest($requestDirectory)
    {
        $indexRequestName = $this->getIndexRequestName();
        $storeRequestName = $this->getStoreRequestName();
        $updateRequestName = $this->getUpdateRequestName();

        try {
            $this->makeIndexRequest($requestDirectory, $indexRequestName);
            $this->makeStoreRequest($requestDirectory, $storeRequestName);
            $this->makeUpdateRequest($requestDirectory, $updateRequestName);
        } catch (\Exception $e) {
            $this->error('Failed to create request');
            $this->line($e->getMessage());
        }
    }

    /**
     * Make Index Request
     * @param $requestDirectory
     * @param $requestName
     */
    public function makeIndexRequest($requestDirectory, $requestName)
    {
        $requestPath = $this->getRequestPath();

        $content = <<<XML
        <?php

        namespace $requestPath;

        use App\Http\Requests\BaseRequest;

        class $requestName extends BaseRequest
        {
            /**
             * Determine if the user is authorized to make this request.
             *
             * @return bool
             */
            public function authorize()
            {
                return true;
            }

            /**
             * Get the validation rules that apply to the request.
             *
             * @return array
             */
            public function rules()
            {
                return [
                    //
                ];
            }
        }
        XML;

        $this->disk->put($requestDirectory . DIRECTORY_SEPARATOR . $requestName . '.php', $content);
    }

    /**
     * Make Store Request
     * @param $requestDirectory
     * @param $requestName
     */
    public function makeStoreRequest($requestDirectory, $requestName)
    {
        $requestPath = $this->getRequestPath();

        $content = <<<XML
        <?php

        namespace $requestPath;

        use App\Http\Requests\BaseRequest;

        class $requestName extends BaseRequest
        {
            /**
             * Determine if the user is authorized to make this request.
             *
             * @return bool
             */
            public function authorize()
            {
                return true;
            }

            /**
             * Get the validation rules that apply to the request.
             *
             * @return array
             */
            public function rules()
            {
                return [
                    //
                ];
            }
        }
        XML;

        $this->disk->put($requestDirectory . DIRECTORY_SEPARATOR . $requestName . '.php', $content);
    }

    /**
     * Make update request
     * @param $requestDirectory
     * @param $requestName
     */
    public function makeUpdateRequest($requestDirectory, $requestName)
    {
        $requestPath = $this->getRequestPath();

        $content = <<<XML
        <?php

        namespace $requestPath;

        use App\Http\Requests\BaseRequest;

        class $requestName extends BaseRequest
        {
            /**
             * Determine if the user is authorized to make this request.
             *
             * @return bool
             */
            public function authorize()
            {
                return true;
            }

            /**
             * Get the validation rules that apply to the request.
             *
             * @return array
             */
            public function rules()
            {
                return [
                    //
                ];
            }
        }
        XML;

        $this->disk->put($requestDirectory . DIRECTORY_SEPARATOR . $requestName . '.php', $content);
    }

    /**
     * Make Model
     * @param $modelDirectory
     * @param $modelName
     */
    public function makeModel($modelDirectory, $modelName)
    {
        $content = <<<XML
        <?php

        namespace App\\$modelDirectory;

        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\Factories\HasFactory;

        class $modelName extends Model
        {
            use HasFactory;
            
            /**
             * The table associated with the model.
             *
             * @var string
             */
            protected \$table = '';

            /**
             * The attributes that are mass assignable.
             *
             * @var array
             */
            protected \$fillable = [
                //
            ];

            /**
             * The attributes that should be hidden for arrays.
             *
             * @var array
             */
            protected \$hidden = [
                //
            ];

            /**
             * The attributes that should be cast to native types.
             *
             * @var array
             */
            protected \$casts = [
                //
            ];
        }
        XML;

        $this->disk->put($modelDirectory . DIRECTORY_SEPARATOR . $modelName . '.php', $content);
    }

    /**
     * Make Policy
     * @param $policyDirectory
     * @param $policyName
     */
    public function makePolicy($policyDirectory, $policyName)
    {
        $content = <<<XML
        <?php

        namespace App\\$policyDirectory;

        use App\Models\Admin;
        use App\Constants\RoleMenuConstant;
        use Illuminate\Auth\Access\HandlesAuthorization;

        class $policyName
        {
            use HandlesAuthorization;

            /**
             * Determine whether the admin can view any models.
             *
             * @param  \App\Models\Admin \$admin
             * @return bool
             */
            public function viewAny(Admin \$admin)
            {
                return true;
                // return \$admin->hasPermission(RoleMenuConstant::CAN_VIEW);
            }

            /**
             * Determine whether the admin can view the model.
             *
             * @param  \App\Models\Admin \$admin
             * @return bool
             */
            public function view(Admin \$admin)
            {
                return true;
                // return \$admin->hasPermission(RoleMenuConstant::CAN_VIEW);
            }

            /**
             * Determine whether the admin can create models.
             *
             * @param  \App\Models\Admin \$admin
             * @return bool
             */
            public function create(Admin \$admin)
            {
                return true;
                // return \$admin->hasPermission(RoleMenuConstant::CAN_CREATE);
            }

            /**
             * Determine whether the admin can update the model.
             *
             * @param  \App\Models\Admin \$admin
             * @return bool
             */
            public function update(Admin \$admin)
            {
                return true;
                // return \$admin->hasPermission(RoleMenuConstant::CAN_UPDATE);
            }

            /**
             * Determine whether the admin can delete the model.
             *
             * @param  \App\Models\Admin \$admin
             * @return bool
             */
            public function delete(Admin \$admin)
            {
                return true;
                // return \$admin->hasPermission(RoleMenuConstant::CAN_DELETE);
            }
        }
        XML;

        $this->disk->put($policyDirectory . DIRECTORY_SEPARATOR . $policyName . '.php', $content);
    }

    /**
     * Make Repository
     * @param $repositoryDirectory
     * @param $repositoryName
     */
    public function makeRepository($repositoryDirectory, $repositoryName)
    {
        $modelName = $this->getModelName();
        $modelNameCamelCase = Str::camel($modelName);

        $content = <<<XML
        <?php

        namespace App\\$repositoryDirectory;

        use Illuminate\Support\Collection;
        use App\Traits\BaseRepository;
        use App\Models\\$modelName;

        class $repositoryName
        {
            use BaseRepository;

            /**
             * @var $modelName \$$modelNameCamelCase
             */
            public function __construct($modelName \$$modelNameCamelCase)
            {
                \$this->model = \$$modelNameCamelCase;
            }

            /**
             * List all data
             * @param array \$params
             * @param array \$columns
             * @return Collection
             */
            public function lists(array \$params, array \$columns = ['*']): Collection
            {
                \$page = \$this->getPage(\$params);
                \$pageSize = \$this->getPageSize(\$params);
                \$query = \$this->model->select(\$columns);
                if (!empty(\$params)) {
                    \$query = \$this->filterParams(\$query, \$params);
                }
                \$query = \$query->orderBy('id', 'desc');
                \$data = \$query->paginate(\$pageSize, \$page); 
                return \$this->pageList(\$data);
            }

            /**
             * Filter params
             * @param \$query
             * @param \$params
             * @return mixed
             */
            public function filterParams(\$query, \$params)
            {
                return \$query;
            }
        }
        XML;

        $this->disk->put($repositoryDirectory . DIRECTORY_SEPARATOR . $repositoryName . '.php', $content);
    }

    /**
     * Make Resource
     * @param $resourceDirectory
     * @param $resourceName
     */
    public function makeResource($resourceDirectory, $resourceName)
    {
        $content = <<<XML
        <?php

        namespace App\\$resourceDirectory;

        class $resourceName 
        {
            protected \$resource;

            /**
             * Constructor.
             * @param \$resource
             */
            public function __construct(\$resource)
            {
                \$this->resource = \$resource->toArray();
            }

            /**
             * Index resource
             * @return array
             */
            public function index(): array
            {
                \$response = \$this->resource;
                foreach (\$response['lists'] as \$key => \$value) {
                    \$response['lists'][\$key] = [
                        //
                    ];
                }

                return \$response;
            }

            /**
             * Show resource
             * @return array
             */
            public function show(): array
            {
                \$result = \$this->resource;
                \$response = [];

                return \$response;
            }

            /**
             * Store resource
             * @return array
             */
            public function store(): array
            {
                \$result = \$this->resource;
                \$response = [];

                return \$response;
            }
        }
        XML;

        $this->disk->put($resourceDirectory . DIRECTORY_SEPARATOR . $resourceName . '.php', $content);
    }

    /**
     * Make Service
     * @param $serviceDirectory
     * @param $serviceName
     */
    public function makeService($serviceDirectory, $serviceName)
    {
        $repositoryName = $this->getRepositoryName();
        $repositoryNameCamelCase = Str::camel($repositoryName);

        $content = <<<XML
        <?php

        namespace App\\$serviceDirectory;

        use Illuminate\Support\Collection;
        use Illuminate\Database\Eloquent\Model;
        use App\Repositories\\$repositoryName;

        class $serviceName 
        {
            protected $repositoryName \$$repositoryNameCamelCase;

            /**
             * Constructor.
             * @param $repositoryName \$$repositoryNameCamelCase
             */
            public function __construct($repositoryName \$$repositoryNameCamelCase)
            {
                \$this->$repositoryNameCamelCase = \$$repositoryNameCamelCase;
            }

            /**
             * Get all data
             * @param array \$params
             * @return Collection
             */
            public function lists(array \$params = []): Collection
            {
                return \$this->{$repositoryNameCamelCase}->lists(\$params);
            }

            /**
             * Create data
             * @param array \$params
             * @return Model
             */
            public function create(array \$params): Model
            {
                return \$this->{$repositoryNameCamelCase}->create(\$params);
            }

            /**
             * Get data by id
             * @param int|string \$id
             * @return Model|null
             */
            public function show(int|string \$id): ?Model
            {
                return \$this->{$repositoryNameCamelCase}->findById(\$id);
            }

            /**
             * Update data
             * @param int|string \$id
             * @param array \$params
             * @return Model|null
             */
            public function update(int|string \$id, array \$params): ?Model
            {
                \$model = \$this->{$repositoryNameCamelCase}->findById(\$id);
                \$model->update(\$params);
                return \$model;
            }

            /**
             * Delete data
             * @param int|string \$id
             * @return bool
             */
            public function delete(int|string \$id): bool
            {
                \$model = \$this->{$repositoryNameCamelCase}->findById(\$id);
                return \$model->delete();
            }
        }
        XML;

        $this->disk->put($serviceDirectory . DIRECTORY_SEPARATOR . $serviceName . '.php', $content);
    }

    /**
     * Get the module name
     * @return string
     */
    public function getModuleName()
    {
        return ucwords($this->argument('module'));
    }

    /**
     * Get the constant directory
     * @return string
     */
    public function getConstantDirectory()
    {
        return $this->constantDirectory;
    }

    /**
     * Get the constant name
     * @return string
     */
    public function getConstantName()
    {
        return $this->getModuleName() . $this->constantNameSuffix;
    }

    /**
     * Get the controller directory
     * @return string
     */
    public function getControllerDirectory()
    {
        $dir = 'Admin';
        if ($this->option('controller')) {
            $dir = DIRECTORY_SEPARATOR . $this->option('controller');
        }
        return 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'V1' . DIRECTORY_SEPARATOR . $dir;
    }

    /**
     * Get the controller directory
     * @return string
     */
    public function getOptionPath()
    {
        $dir = 'Admin';
        if ($this->option('controller')) {
            $dir = $this->option('controller');
        }
        return $dir;
    }

    public function getRequestPath()
    {
        $moduleName = $this->getModuleName();
        $optionPath = $this->getOptionPath();
        $indexRequestPath = 'App\\Http\\Requests\\' . $optionPath . '\\' . $moduleName;
        return $indexRequestPath;
    }

    public function getIndexRequestPath()
    {
        $indexRequestName = $this->getIndexRequestName();
        $requestPath = $this->getRequestPath();
        return $requestPath . '\\' . $indexRequestName;
    }

    public function getStoreRequestPath()
    {
        $storeRequestName = $this->getStoreRequestName();
        $requestPath = $this->getRequestPath();
        return $requestPath . '\\' . $storeRequestName;
    }

    public function getUpdateRequestPath()
    {
        $updateRequestName = $this->getUpdateRequestName();
        $requestPath = $this->getRequestPath();
        return $requestPath . '\\' . $updateRequestName;
    }

    /**
     * Get the controller name
     * @return string
     */
    public function getControllerName()
    {
        return $this->getModuleName() . $this->controllerNameSuffix;
    }

    /**
     * Get the model directory
     * @return string
     */
    public function getRequestDirectory()
    {
        $dir = 'Admin';
        if ($this->option('controller')) {
            $dir = DIRECTORY_SEPARATOR . $this->option('controller');
        }
        return 'Http' . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $this->getModuleName();
    }

    /**
     * Get the request directory
     * @return string
     */
    public function getIndexRequestName()
    {
        return $this->getModuleName() . $this->indexRequestNameSuffix;
    }

    /**
     * Get the request directory
     * @return string
     */
    public function getStoreRequestName()
    {
        return $this->getModuleName() . $this->storeRequestNameSuffix;
    }

    /**
     * Get the request directory
     * @return string
     */
    public function getUpdateRequestName()
    {
        return $this->getModuleName() . $this->updateRequestNameSuffix;
    }

    /**
     * Get the model directory
     * @return string
     */
    public function getModelDirectory()
    {
        return $this->modelDirectory;
    }

    /**
     * Get the model name
     * @return string
     */
    public function getModelName()
    {
        return $this->getModuleName() . $this->modelNameSuffix;
    }

    /**
     * Get the policy directory
     * @return string
     */
    public function getPolicyDirectory()
    {
        return $this->policyDirectory;
    }

    /**
     * Get the policy name
     * @return string
     */
    public function getPolicyName()
    {
        return $this->getModuleName() . $this->policyNameSuffix;
    }

    /**
     * Get the repository directory
     * @return string
     */
    public function getRepositoryDirectory()
    {
        return $this->repositoryDirectory;
    }

    /**
     * Get the repository name
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->getModuleName() . $this->repositoryNameSuffix;
    }

    /**
     * Get the resource directory
     * @return string
     */
    public function getResourceDirectory()
    {
        return $this->resourceDirectory;
    }

    /**
     * Get the resource name
     * @return string
     */
    public function getResourceName()
    {
        return $this->getModuleName() . $this->resourceNameSuffix;
    }

    /**
     * Get the service directory
     * @return string
     */
    public function getServiceDirectory()
    {
        return $this->serviceDirectory;
    }

    /**
     * Get the service name
     * @return string
     */
    public function getServiceName()
    {
        return $this->getModuleName() . $this->serviceNameSuffix;
    }
}

<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

use Illuminate\Support\Str;

$base = dirname(__DIR__);
$modelDir = $base.'/app/Models/Shop';
$requestDir = $base.'/app/Http/Requests/Api/V1/Shop';
$controllerDir = $base.'/app/Http/Controllers/Api/V1/Shop';

foreach ([$requestDir, $controllerDir] as $d) {
    if (! is_dir($d)) {
        mkdir($d, 0777, true);
    }
}

$files = glob($modelDir.'/*.php');
$models = [];
foreach ($files as $f) {
    $name = basename($f, '.php');
    if (str_ends_with($name, 'Translation')) {
        continue;
    }
    $models[] = $name;
}
sort($models);

function extractFillable(string $path): array
{
    $src = file_get_contents($path);
    if (! preg_match('/protected\s+\$fillable\s*=\s*\[(.*?)\];/s', $src, $m)) {
        return [];
    }
    $inner = $m[1];
    preg_match_all("/'([^']+)'/", $inner, $keys);

    return $keys[1] ?? [];
}

function ruleForKey(string $key, bool $forStore, string $modelShort): string
{
    $sometimes = $forStore ? 'nullable' : 'sometimes';

    if (str_ends_with($key, '_id')) {
        return "'{$key}' => ['{$sometimes}', 'integer']";
    }
    if (in_array($key, ['enabled', 'tracked', 'immutable', 'exclusive', 'coupon_based', 'applies_to_discounted', 'subscribed_to_newsletter', 'included_in_price', 'neutral', 'locked'], true)) {
        return "'{$key}' => ['{$sometimes}', 'boolean']";
    }
    if ($key === 'amount' && $modelShort === 'TaxRate') {
        return "'{$key}' => ['{$sometimes}', 'numeric']";
    }
    if (in_array($key, ['priority', 'position', 'version', 'quantity', 'unit_price', 'original_unit_price', 'on_hand', 'on_hold', 'minimum_price', 'price', 'original_price', 'lowest_price_before_discount', 'items_total', 'adjustments_total', 'total', 'units_total', 'used', 'usage_limit'], true)) {
        return "'{$key}' => ['{$sometimes}', 'integer']";
    }
    if ($key === 'amount' || $key === 'adjustment_amount') {
        return "'{$key}' => ['{$sometimes}', 'integer']";
    }
    if ($key === 'configuration' || $key === 'details' || $key === 'gateway_config') {
        return "'{$key}' => ['{$sometimes}', 'array']";
    }
    if ($key === 'birthday' || str_ends_with($key, '_at')) {
        return "'{$key}' => ['{$sometimes}', 'date']";
    }
    if (in_array($key, ['description', 'notes', 'meta_description', 'meta_keywords'], true)) {
        return "'{$key}' => ['{$sometimes}', 'string']";
    }

    return "'{$key}' => ['{$sometimes}', 'string', 'max:65535']";
}

foreach ($models as $short) {
    $path = $modelDir.'/'.$short.'.php';
    $fillable = extractFillable($path);

    $storeRules = [];
    $updateRules = [];
    foreach ($fillable as $k) {
        $storeRules[] = ruleForKey($k, true, $short);
        $updateRules[] = ruleForKey($k, false, $short);
    }
    $storeRules = array_filter($storeRules);
    $updateRules = array_filter($updateRules);

    $storeBody = $storeRules === [] ? '' : implode(",\n            ", $storeRules);
    $updateBody = $updateRules === [] ? '' : implode(",\n            ", $updateRules);

    $storeReq = "Store{$short}Request";
    $updateReq = "Update{$short}Request";

    $storeFile = "{$requestDir}/{$storeReq}.php";
    $storeInner = $storeBody === '' ? '' : "\n            {$storeBody},\n        ";
    $storePhp = <<<PHP
<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Shop;

use Illuminate\Foundation\Http\FormRequest;

final class {$storeReq} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [{$storeInner}];
    }
}

PHP;
    file_put_contents($storeFile, $storePhp);

    $updateFile = "{$requestDir}/{$updateReq}.php";
    $updateInner = $updateBody === '' ? '' : "\n            {$updateBody},\n        ";
    $updatePhp = <<<PHP
<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Shop;

use Illuminate\Foundation\Http\FormRequest;

final class {$updateReq} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [{$updateInner}];
    }
}

PHP;
    file_put_contents($updateFile, $updatePhp);

    $iface = "App\\Repositories\\Contracts\\Shop\\{$short}RepositoryInterface";
    $storeImport = "App\\Http\\Requests\\Api\\V1\\Shop\\{$storeReq}";
    $updateImport = "App\\Http\\Requests\\Api\\V1\\Shop\\{$updateReq}";

    $controller = "{$short}Controller";
    $controllerFile = "{$controllerDir}/{$controller}.php";
    $var = Str::camel($short);
    $controllerPhp = <<<PHP
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Shop;

use {$iface};
use {$storeImport};
use {$updateImport};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class {$controller} extends Controller
{
    public function __construct(
        private readonly {$short}RepositoryInterface \${$var}Repository,
    ) {}

    public function index(Request \$request): JsonResponse
    {
        \$perPage = min(max((int) \$request->query('per_page', 15), 1), 100);

        return response()->json(\$this->{$var}Repository->paginate(\$perPage));
    }

    public function store({$storeReq} \$request): JsonResponse
    {
        \$model = \$this->{$var}Repository->create(\$request->validated());

        return response()->json(\$model, 201);
    }

    public function show(int \$id): JsonResponse
    {
        return response()->json(\$this->{$var}Repository->findOrFail(\$id));
    }

    public function update({$updateReq} \$request, int \$id): JsonResponse
    {
        \$model = \$this->{$var}Repository->findOrFail(\$id);
        \$model = \$this->{$var}Repository->update(\$model, array_filter(\$request->validated(), static fn (\$v) => \$v !== null));

        return response()->json(\$model);
    }

    public function destroy(int \$id): JsonResponse
    {
        \$model = \$this->{$var}Repository->findOrFail(\$id);
        \$this->{$var}Repository->delete(\$model);

        return response()->json(null, 204);
    }
}

PHP;
    file_put_contents($controllerFile, $controllerPhp);
}

echo 'Generated '.count($models)." API layers.\n";

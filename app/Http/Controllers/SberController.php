<?php

namespace App\Http\Controllers;

use App\Endpoint;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceCountResource;
use App\Mapping\ServiceMethodCountUrls;
use App\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SberController extends Controller
{
    public function request(Request $request)
    {
        $endpoint = Endpoint::query()
            ->where('user_id', Auth::id())
            ->where('name', $request->name)
            ->first();

        if (!$endpoint) {
            throw new HttpResponseException(response(null, 404));
        }

        $method = 'get';
        if ($request->http_method == 'POST') {
            $method = 'post';
        }

        $url = $endpoint->uri . $request->url;
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withHeaders([
            'x-auth-token' => (Auth::user())->sber_token
        ])->$method($url, $request->params);

        return response(['data' => $response->json()], $response->status());
    }

    public function getActiveServices(Request $request)
    {
        $projectsCollection = new Collection();
        $createdProject = rand(1, 4);
        $inx = 0;
        while ($createdProject) {
            $createdProject--;
            $project = new Project();
            $inx++;
            $project->id = $inx;
            $countServiceCollection = new Collection();
            $endpoints = Endpoint::query()->where('user_id', Auth::user()->id)->get();
            foreach ($endpoints as $endpoint) {
                if (ServiceMethodCountUrls::$methodsList[$endpoint->name] ?? false){
                    $endpointMappingInfo = ServiceMethodCountUrls::$methodsList[$endpoint->name];
                    $url = $endpoint->uri . $endpointMappingInfo['url'];
                    /** @var \Illuminate\Http\Client\Response $response */
                    $response = Http::withHeaders([
                        'x-auth-token' => (Auth::user())->sber_token
                    ])->get($url);
                    $count = $response->json()[$endpointMappingInfo['countMethod']] ?? null;
                    if ($count && ($inx != 1 && (rand(0, 100) > 50))) {
                        $endpoint->countElements = $count;
                        $endpoint->full_name = $endpointMappingInfo['name'];
                        $countServiceCollection->add($endpoint);
                    }
                }
            }
            $project->services = $countServiceCollection;
            $projectsCollection->add($project);
        }
        return ProjectResource::collection($projectsCollection);
    }
}

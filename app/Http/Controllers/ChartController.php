<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function builder()
    {
        return view('chartbuilder');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'chart_type' => 'required|string',
            'data_source' => 'required|string',
            'config' => 'nullable|array'
        ]);

        $chart = Chart::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chart saved successfully',
            'chart' => $chart
        ]);
    }

    public function index()
    {
        $charts = Chart::orderBy('created_at', 'desc')->get();
        return response()->json($charts);
    }

    public function destroy($id)
    {
        $chart = Chart::findOrFail($id);
        $chart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chart deleted successfully'
        ]);
    }

    public function petsBySpecies()
    {
        $data = Pet::select('species', DB::raw('count(*) as count'))
            ->groupBy('species')
            ->get();

        return response()->json([
            'labels' => $data->pluck('species'),
            'values' => $data->pluck('count')
        ]);
    }

    public function petsByStatus()
    {
        $data = Pet::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'labels' => $data->pluck('status'),
            'values' => $data->pluck('count')
        ]);
    }

    public function petsByAge()
    {
        $data = Pet::select(
            DB::raw('CASE 
                WHEN age < 1 THEN "Puppy/Kitten"
                WHEN age BETWEEN 1 AND 3 THEN "Young"
                WHEN age BETWEEN 4 AND 7 THEN "Adult"
                ELSE "Senior"
            END as age_group'),
            DB::raw('count(*) as count')
        )
        ->groupBy('age_group')
        ->get();

        return response()->json([
            'labels' => $data->pluck('age_group'),
            'values' => $data->pluck('count')
        ]);
    }

    public function petsByGender()
    {
        $data = Pet::select('sex', DB::raw('count(*) as count'))
            ->groupBy('sex')
            ->get();

        return response()->json([
            'labels' => $data->pluck('sex'),
            'values' => $data->pluck('count')
        ]);
    }

    public function adoptionRequestsByStatus()
    {
        $data = AdoptionRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'labels' => $data->pluck('status'),
            'values' => $data->pluck('count')
        ]);
    }

    public function adoptionRequestsByMonth()
    {
        $data = AdoptionRequest::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return response()->json([
            'labels' => $data->pluck('month'),
            'values' => $data->pluck('count')
        ]);
    }

    public function mostRequestedPets()
    {
        $data = AdoptionRequest::select('pet_id', DB::raw('count(*) as count'))
            ->with('pet:id,name')
            ->groupBy('pet_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $data->map(fn($item) => $item->pet->name ?? 'Unknown'),
            'values' => $data->pluck('count')
        ]);
    }
}

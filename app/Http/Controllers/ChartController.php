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
            'data_source' => 'required|string'
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

    public function averageAgeBySpecies()
    {
        $data = Pet::select('species', DB::raw('AVG(age) as avg_age'))
            ->groupBy('species')
            ->get();

        return response()->json([
            'labels' => $data->pluck('species'),
            'values' => $data->pluck('avg_age')->map(fn($v) => round($v, 1))
        ]);
    }

    public function genderDistributionBySpecies()
    {
        $species = Pet::select('species')->distinct()->pluck('species');
        $datasets = [];

        foreach (['Male', 'Female'] as $gender) {
            $data = [];
            foreach ($species as $spec) {
                $count = Pet::where('species', $spec)
                    ->where('sex', $gender)
                    ->count();
                $data[] = $count;
            }
            $datasets[] = [
                'label' => $gender,
                'data' => $data
            ];
        }

        return response()->json([
            'labels' => $species,
            'datasets' => $datasets
        ]);
    }

    public function newestPets()
    {
        $data = Pet::orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['name', 'created_at']);

        return response()->json([
            'labels' => $data->pluck('name'),
            'values' => $data->map(fn($pet) => max(1, (int)$pet->created_at->diffInDays(now())))
        ]);
    }

    public function oldestPets()
    {
        $data = Pet::where('status', 'available')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get(['name', 'created_at']);

        return response()->json([
            'labels' => $data->pluck('name'),
            'values' => $data->map(fn($pet) => max(1, (int)$pet->created_at->diffInDays(now())))
        ]);
    }

    public function petsCreatedByMonth()
    {
        $data = Pet::select(
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

    public function requestsByUser()
    {
        $data = AdoptionRequest::select('name', DB::raw('count(*) as count'))
            ->groupBy('name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $data->pluck('name'),
            'values' => $data->pluck('count')
        ]);
    }

    public function mostRequestedSpecies()
    {
        $data = AdoptionRequest::join('pets', 'adoption_requests.pet_id', '=', 'pets.id')
            ->select('pets.species', DB::raw('count(*) as count'))
            ->groupBy('pets.species')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'labels' => $data->pluck('species'),
            'values' => $data->pluck('count')
        ]);
    }

    public function userRegistrationsOverTime()
    {
        $data = \App\Models\User::select(
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

    public function mostRequestedAgeGroups()
    {
        $data = AdoptionRequest::join('pets', 'adoption_requests.pet_id', '=', 'pets.id')
            ->select(
                DB::raw('CASE 
                    WHEN pets.age < 1 THEN "Puppy/Kitten"
                    WHEN pets.age BETWEEN 1 AND 3 THEN "Young"
                    WHEN pets.age BETWEEN 4 AND 7 THEN "Adult"
                    ELSE "Senior"
                END as age_group'),
                DB::raw('count(*) as count')
            )
            ->groupBy('age_group')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'labels' => $data->pluck('age_group'),
            'values' => $data->pluck('count')
        ]);
    }

    public function seasonalTrends()
    {
        $data = AdoptionRequest::select(
            DB::raw('QUARTER(created_at) as quarter'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('year', 'quarter')
        ->orderBy('year')
        ->orderBy('quarter')
        ->get();

        $labels = $data->map(function($item) {
            $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            return $quarters[$item->quarter - 1] . ' ' . $item->year;
        });

        return response()->json([
            'labels' => $labels,
            'values' => $data->pluck('count')
        ]);
    }

    // Public-facing chart methods (only available pets)
    public function availablePetsBySpecies()
    {
        $data = Pet::select('species', DB::raw('count(*) as count'))
            ->where('status', 'available')
            ->groupBy('species')
            ->get();

        return response()->json([
            'labels' => $data->pluck('species'),
            'values' => $data->pluck('count')
        ]);
    }

    public function availablePetsByAge()
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
        ->where('status', 'available')
        ->groupBy('age_group')
        ->get();

        return response()->json([
            'labels' => $data->pluck('age_group'),
            'values' => $data->pluck('count')
        ]);
    }

    public function availablePetsByGender()
    {
        $data = Pet::select('sex', DB::raw('count(*) as count'))
            ->where('status', 'available')
            ->groupBy('sex')
            ->get();

        return response()->json([
            'labels' => $data->pluck('sex'),
            'values' => $data->pluck('count')
        ]);
    }
}

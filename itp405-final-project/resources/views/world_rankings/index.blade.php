@extends('layout')

@section('title', 'World Archery Rankings')

@section('main')
    <h1 class="mb-4">World Archery Rankings</h1>

    <h2>Recurve Men</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Country</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menRankings as $athlete)
                <tr>
                    <td>{{ $athlete['Rnk'] }}</td>
                    <td>{{ $athlete['Athlete']['GName'] }} {{ $athlete['Athlete']['FName'] }}</td>
                    <td>{{ $athlete['Athlete']['NOC'] }}</td>
                    <td>{{ $athlete['Points'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Recurve Women</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Country</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($womenRankings as $athlete)
                <tr>
                    <td>{{ $athlete['Rnk'] }}</td>
                    <td>{{ $athlete['Athlete']['GName'] }} {{ $athlete['Athlete']['FName'] }}</td>
                    <td>{{ $athlete['Athlete']['NOC'] }}</td>
                    <td>{{ $athlete['Points'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@endsection

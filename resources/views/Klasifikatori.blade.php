@extends ('layout.app')

@section('content')

<div style="width: 100%; display: flex; justify-content: center; align-items: center; margin-top: 16px;">
    <div style="background: #69c6d3; padding: 32px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); width: 100%; display: flex; gap: 32px; align-items: center;">


        <div style="flex: 1; min-width: 0;">

            <div style="width: 100%; display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="background: #ffffff; padding: 5px 15px; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); font-weight: bold;">
                    Klasifikatori
                </div>
            </div>

            <nav class="navigacija" style=" padding: 8px 10px; display: flex; gap: 80px; align-items: center; justify-content: center;">
                <a href="/Darbinieki/jauns">Jauns ieraksts</a>
                <a href="/VagonaRaksturojums">Vagona raksturojums</a>
                <a href="/Veidi">Vagonu veidi</a>
                <a href="/Kravas">Krava</a>
                <a href="/Amati">Amati</a>
            </nav>

        </div>

    </div>
</div>

@endsection













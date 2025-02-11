<div class="d-flex flex-column align-items-center">
    <div class="footer__links d-flex">
        <a href="/trainer/aed" class="text-muted">Dispositivo</a>
        <a href="{{ route('logout') }}" class="text-muted" style="cursor: pointer;" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Finalizar sesión</a>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
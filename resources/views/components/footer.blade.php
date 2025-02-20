<div class="d-flex justify-content-between align-items-center my-3 w-100 px-3">
    <div class="footer__links d-flex gap-3">
        <a href="/trainer" class="text-muted">Dispositivo</a>
        <a href="{{ route('logout') }}" class="text-muted" style="cursor: pointer;" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Finalizar sesión
        </a>
    </div>
    <div class="text-muted small">
        Hecho con <i class="ri-heart-pulse-fill text-danger" title="Hecho con amor"></i> por <a href="https://github.com/lucasprezrguez/desa_trainer_grupo_6/graphs/contributors" class="text-muted" target="_blank"><u>Grupo 6</u></a>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
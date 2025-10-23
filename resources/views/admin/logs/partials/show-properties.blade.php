<div class="modal fade" id="showPropertiesModal{{ $log->id }}" tabindex="-1"
  aria-labelledby="showPropertiesModalLabel{{ $log->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showPropertiesModalLabel{{ $log->id }}">Activity Details -
          {{ ucfirst($log->event) }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if($log->properties)
          <pre><code class="language-json">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
        @else
          <p class="text-muted">No properties available</p>
        @endif
      </div>
    </div>
  </div>
</div>
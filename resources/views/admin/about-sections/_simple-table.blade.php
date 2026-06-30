<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="h6 mb-0">{{ $heading }}</h3>
  <a href="{{ $createRoute }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Add</a>
</div>
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>Title</th>
        <th>Order</th>
        <th>Status</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $item)
        <tr>
          <td>
            <div class="fw-semibold">{{ $item->title }}</div>
            <div class="small text-muted">{{ str($item->description)->limit(90) }}</div>
          </td>
          <td>{{ $item->display_order }}</td>
          <td><span class="badge {{ $item->status ? 'bg-success' : 'bg-secondary' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span></td>
          <td class="text-end">
            <div class="btn-group btn-group-sm">
              <a href="{{ route($editName, $item) }}" class="btn btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route($deleteName, $item) }}" onsubmit="return confirm('Delete this item?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted py-4">No items found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
{{ $items->links() }}

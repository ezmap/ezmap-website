@extends('layouts.master')
@section('title', 'Admin Panel')

@section('content')

  <flux:heading size="xl" level="1">Admin Panel</flux:heading>

  <flux:tab.group class="mt-6">
    <flux:tabs>
      <flux:tab name="users" icon="users">Users</flux:tab>
      <flux:tab name="themes" icon="swatch">Themes</flux:tab>
      <flux:tab name="icons" icon="map-pin">Icons</flux:tab>
    </flux:tabs>

    {{-- Users Tab --}}
    <flux:tab.panel name="users">
      <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <flux:heading size="lg" level="2">User Management</flux:heading>
          <flux:text size="sm">
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
            of {{ $users->total() }} users
          </flux:text>
        </div>

        <form method="GET" action="{{ url('admin') }}" class="flex items-end gap-3">
          <div class="flex-1">
            <flux:input name="search" placeholder="Search users by name or email..." :value="$search" icon="magnifying-glass" />
          </div>
          <flux:button type="submit" variant="primary">Search</flux:button>
          @if($search)
            <flux:button href="{{ url('admin') }}">Clear</flux:button>
          @endif
        </form>

        @if($users->count() > 0)
          <div class="space-y-2">
            @foreach($users as $user)
              <div class="flex items-center justify-between p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800">
                <div>
                  <a href="{{ route('stealth', $user) }}" class="font-medium text-zinc-900 dark:text-white hover:underline">{{ $user->name }}</a>
                  <span class="ml-2 text-sm text-zinc-500">{{ $user->email }}</span>
                </div>
                @if($user->id != 1)
                  <form method="POST" action="{{ route('admin.deleteUser', $user->id) }}" onsubmit="return confirmDelete('{{ $user->name }}')">
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" variant="danger" size="xs" icon="trash" />
                  </form>
                @endif
              </div>
            @endforeach
          </div>

          <div class="mt-4">
            {{ $users->links() }}
          </div>
        @else
          <flux:callout icon="information-circle">
            <flux:callout.text>
              @if($search)
                No users found matching "{{ $search }}".
              @else
                No users found.
              @endif
            </flux:callout.text>
          </flux:callout>
        @endif
      </div>
    </flux:tab.panel>

    {{-- Themes Tab --}}
    <flux:tab.panel name="themes">
      <flux:card class="space-y-6">
        <flux:heading size="lg" level="2">Populate Snazzy Themes</flux:heading>
        <flux:text>There are currently <strong>{{ \App\Models\Theme::count() }}</strong> Snazzy Themes installed</flux:text>

        <form action="{{ route('populateThemes') }}" method="POST" class="space-y-4">
          @csrf
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <flux:select name="tag" label="Tag" placeholder="Please Select">
              @foreach (['colorful','complex','dark','greyscale','light','monochrome','no-labels','simple','two-tone'] as $tag)
                <flux:select.option value="{{ $tag }}">{{ $tag }}</flux:select.option>
              @endforeach
            </flux:select>

            <flux:select name="color" label="Colour" placeholder="Please Select">
              @foreach( ['black','blue','gray','green','multi','orange','purple','red','white','yellow'] as $color)
                <flux:select.option value="{{ $color }}">{{ $color }}</flux:select.option>
              @endforeach
            </flux:select>

            <flux:select name="sort" label="Sort By" placeholder="Please Select">
              @foreach( ['popular', 'recent'] as $option)
                <flux:select.option value="{{ $option }}">{{ $option }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <flux:input name="text" label="Search Text" placeholder="Apple Map" />
            <flux:input name="pageSize" label="Number Per Page" placeholder="12" />
            <flux:input name="page" label="Page" placeholder="1" />
          </div>

          <flux:button type="submit" variant="primary" class="w-full">Populate Themes</flux:button>
        </form>
      </flux:card>
    </flux:tab.panel>

    {{-- Icons Tab --}}
    <flux:tab.panel name="icons">
      <flux:card class="space-y-6">
        <flux:heading size="lg" level="2">Add Marker Icons</flux:heading>

        <form action="{{ route('addMarkerIcon') }}" method="POST" class="space-y-4">
          @csrf
          <flux:input name="iconName" label="Icon Name" placeholder="Icon Name" />
          <flux:input name="iconURL" label="Icon URL" placeholder="Icon URL" />
          <flux:button type="submit" class="w-full">Submit</flux:button>
        </form>

        <flux:button href="{{ route('AZPopulate') }}" class="w-full">Populate by code</flux:button>
      </flux:card>
    </flux:tab.panel>
  </flux:tab.group>

@endsection

@push('page-scripts')
<script>
function confirmDelete(userName) {
    return confirm('Are you sure you want to permanently delete the account for "' + userName + '"?\n\nThis action cannot be undone and will delete:\n- The user account\n- All maps created by this user\n- All custom icons uploaded by this user');
}
</script>
@endpush

  <header>
      @php
          $headerMenu = App\Models\Menu::where('position', 'header')
              ->with([
                  'menus' => function ($q) {
                      $q->where('enabled', true)->with([
                          'children' => function ($q2) {
                              $q2->where('enabled', true);
                          },
                      ]);
                  },
              ])
              ->first();
          $menuItems = $headerMenu->menus->where('enabled', true);
      @endphp
      <nav class="navbar navbar-expand-xl bg-common py-28 position-absolute z-3 w-100">
          <div class="container">

              {{-- Logo  --}}
              <a class="navbar-brand d-inline" href="{{ route('home') }}">
                  <img src="{{ getImageUrl($settings['store_logo']) }}" alt="Bangladesh Flag & NPC Logo" class="img-fluid">
              </a>

              {{-- Mobile Menu Button --}}
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                  <span class="navbar-toggler-icon"></span>
              </button>

              {{-- Navigation Menu --}}
              <div class="collapse navbar-collapse justify-content-end custom-nav-manu" id="navbarNav">
                  <ul class="navbar-nav gap-md-4 gap-3 mt-3 mt-lg-0 text-md-center text-lg-start">

                      @foreach ($menuItems as $item)
                          @if ($item->children->count() > 0)
                              <li class="nav-item dropdown">
                                  <a class="nav-link p-0 gap-1 dropdown-toggle" href="#" id="eventsDropdown"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      <span>{{ $item->name }}</span>
                                      <svg width="13" height="8" viewBox="0 0 13 8" fill="none"
                                          xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M5.65703 7.071L2.66411e-05 1.414L1.41403 -4.94551e-07L6.36403 4.95L11.314 -6.18079e-08L12.728 1.414L7.07103 7.071C6.8835 7.25847 6.62919 7.36379 6.36403 7.36379C6.09886 7.36379 5.84455 7.25847 5.65703 7.071Z"
                                              fill="white" />
                                      </svg>
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="eventsDropdown">
                                      @foreach ($item->children as $child)
                                          <li><a class="dropdown-item"
                                                  href="{{ $child->url }}">{{ $child->name }}</a></li>
                                      @endforeach
                                  </ul>
                              </li>
                          @else
                              <li class="nav-item">
                                  <a class="nav-link p-0"
                                      href="{{ $item->url }}"><span>{{ $item->name }}</span></a>
                              </li>
                          @endif
                      @endforeach
                  </ul>
              </div>
          </div>
      </nav>
  </header>

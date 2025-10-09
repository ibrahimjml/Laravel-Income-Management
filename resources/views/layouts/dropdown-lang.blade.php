<!-- language dropdown -->
<div class="dropdown">
  <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button"
    id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    @if(app()->getLocale() === 'en')
      <span class="fi fi-us fis"></span>
    @elseif(app()->getLocale() === 'ar')
      <span class="fi fi-lb fis"></span>
    @endif
    <span>
      @if(app()->getLocale() == 'ar')
      <b>العربية</b>
      @else
      <b>English</b>
      @endif
    </span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="languageDropdown">
    <li>
      <a class="dropdown-item d-flex align-items-center gap-2"
        href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">
        <span class="fi fi-us fis"></span>
        <span>English</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item d-flex align-items-center gap-2"
        href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}">
        <span class="fi fi-lb fis"></span>
        <span>العربية</span>
      </a>
    </li>
  </ul>
</div>
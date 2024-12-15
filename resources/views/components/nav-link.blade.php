@props(['active' => false])

<li class="list-none"><a {{ $attributes }} class="{{ $active ? 'font-semibold text-yellow-500' : 'text-white hover:text-gray-400' }} block py-2 px-4 md:p-1 text-gray-900 text-lg rounded hover:bg-gray-100 hover:font-semibold md:hover:bg-transparent md:hover:text-yellow-500 md:dark:hover:text-yellow-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 hover-underline-animation aria-current="{{ $active ? 'page' : false }} {{ request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/', request()->header('User-Agent')) ? 'block' : '' }}">{{ $slot }}</a></li>
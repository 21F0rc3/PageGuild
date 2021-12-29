<div class="accordion-item">
    <h2 class="accordion-header" id="heading{{ $filterName }}">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $filterName }}" aria-expanded="true" aria-controls="collapse{{ $filterName }}">
            {{ $filterName }}
        </button>
    </h2>
    <div id="collapse{{ $filterName }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $filterName }}">
        <div class="accordion-body">
            {{-- A forma como os href são preenchidos muda consoante o tipo de filtro do accordion --}}
            @switch ($filterName)
                @case('Author')
                    @foreach ($options as $filterResult)
                        <a class="navbar-brand" href="/search/results/filter/{{ $url[0] }}/{{ $filterResult->id }}/{{ $url[2] }}/{{ $url[3] }}/{{ $url[4] }}">
                            <p class="text-dark">{{ $filterResult->name }} ({{ $filterResult->count }})</p>
                        </a>
                    @endforeach
                    @break

                @case('Publisher')
                    @foreach ($options as $filterResult)
                        <a class="navbar-brand" href="/search/results/filter/{{ $url[0] }}/{{ $url[1] }}/{{ $filterResult->id }}/{{ $url[3] }}/{{ $url[4] }}">
                            <p class="text-dark">{{ $filterResult->name }} ({{ $filterResult->count }})</p>
                        </a>
                    @endforeach
                    @break

                @case('Genre')
                    @foreach ($options as $filterResult)
                        <a class="navbar-brand" href="/search/results/filter/{{ $url[0] }}/{{ $url[1] }}/{{ $url[2] }}/{{ $filterResult->id }}/{{ $url[4] }}">
                            <p class="text-dark">{{ $filterResult->name }} ({{ $filterResult->count }})</p>
                        </a>
                    @endforeach
                    @break
                
                @case('Publication_year')
                    @foreach ($options as $filterResult)
                        <a class="navbar-brand" href="/search/results/filter/{{ $url[0] }}/{{ $url[1] }}/{{ $url[2] }}/{{ $url[3] }}/{{ $filterResult->publication_year }}">
                            <p class="text-dark">{{ $filterResult->publication_year }} ({{ $filterResult->count }})</p>
                        </a>
                    @endforeach
                    @break

            @endswitch
        </div>
    </div>
</div>
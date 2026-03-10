<x-layout>
    <x-slot name="header">
        <h1 class="display-4 fw-bold">Medewerkers</h1>
    </x-slot>

    

    <x-slot>
        <div class="medewerkers-table-wrapper">
            <div class="before-medewerkers-table">
                <h2>Werknemers beheren</h2>
                <a href="/employees/create" class="create-employee-button">+</a>
            </div>

            @forelse($employees as $employee)
        <table class="medewerkers-table">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Naam werknemer</th>
                <th class="px-4 py-2 text-left">Specialiteit</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Acties</th>
            </tr>
        </thead>
        <tbody>
            
                <tr>
                    <td class="px-4 py-2">{{ $employee->name }}</td>
                    <td class="px-4 py-2">{{ $employee->specialiteit }}</td>
                    <td class="px-4 py-2">{{ $employee->email }}</td>
                    <td>
                        <div class="medewerkers-table-buttons">
                        <!-- Edit knop -->
                        <a href="{{ route('edit_employee', $employee->id) }}" class="medewerkers-table-button">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 15V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V5C1 4.46957 1.21071 3.96086 1.58579 3.58579C1.96086 3.21071 2.46957 3 3 3H7" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.5 14.8L21 5.2L16.8 1L7.3 10.5L7 15L11.5 14.8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                        </a>

                        <!-- Delete formulier -->
                        <form method="POST"action="{{ route('delete_employee', $employee->id) }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Weet je zeker dat je deze werknemer wilt verwijderen?')" type="submit" class="medewerkers-table-button">
                                <svg width="21" height="26" viewBox="0 0 21 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.5769 6.46154H2.42308C1.97723 6.46154 1.61538 6.1005 1.61538 5.65385C1.61538 5.208 1.97723 4.84615 2.42308 4.84615H18.5769C19.0228 4.84615 19.3846 5.208 19.3846 5.65385C19.3846 6.1005 19.0228 6.46154 18.5769 6.46154ZM17.7692 22.6154C17.7692 23.5071 17.0455 24.2308 16.1538 24.2308H4.84615C3.95446 24.2308 3.23077 23.5071 3.23077 22.6154V8.07692H17.7692V22.6154ZM8.07692 2.42308C8.07692 1.97642 8.43877 1.61538 8.88462 1.61538H12.1154C12.5612 1.61538 12.9231 1.97642 12.9231 2.42308V3.23077H8.07692V2.42308ZM19.3846 3.23077H14.5385V1.61538C14.5385 0.723692 13.8148 0 12.9231 0H8.07692C7.18523 0 6.46154 0.723692 6.46154 1.61538V3.23077H1.61538C0.723692 3.23077 0 3.95446 0 4.84615V6.46154C0 7.35323 0.723692 8.07692 1.61538 8.07692V22.6154C1.61538 24.3996 3.06196 25.8462 4.84615 25.8462H16.1538C17.938 25.8462 19.3846 24.3996 19.3846 22.6154V8.07692C20.2763 8.07692 21 7.35323 21 6.46154V4.84615C21 3.95446 20.2763 3.23077 19.3846 3.23077ZM10.5 22.6154C10.9458 22.6154 11.3077 22.2543 11.3077 21.8077V12.1154C11.3077 11.6695 10.9458 11.3077 10.5 11.3077C10.0542 11.3077 9.69231 11.6695 9.69231 12.1154V21.8077C9.69231 22.2543 10.0542 22.6154 10.5 22.6154ZM6.46154 22.6154C6.90738 22.6154 7.26923 22.2543 7.26923 21.8077V12.1154C7.26923 11.6695 6.90738 11.3077 6.46154 11.3077C6.01569 11.3077 5.65385 11.6695 5.65385 12.1154V21.8077C5.65385 22.2543 6.01569 22.6154 6.46154 22.6154ZM14.5385 22.6154C14.9843 22.6154 15.3462 22.2543 15.3462 21.8077V12.1154C15.3462 11.6695 14.9843 11.3077 14.5385 11.3077C14.0926 11.3077 13.7308 11.6695 13.7308 12.1154V21.8077C13.7308 22.2543 14.0926 22.6154 14.5385 22.6154Z" fill="black"/>
                                </svg>
                            </button>
                        </form>
                        </div>
                    </td>
                </tr>

                 @empty
            <div>
             <p class="px-4">Druk op het plusje om een werknemer aan te maken.</p>
            </div>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Voeg eventueel bovenaan een "Add new" knop toe -->
<a href="#" class="px-4 py-2 bg-red-800 text-white rounded float-right mb-2">
    +
</a>
    </x-slot>
</x-layout>
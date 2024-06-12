  <table class="table table-xs table-zebra mb-2">
      <thead>
          <tr>
              <th>
                  {{ __('expense.date') }}
              </th>
              <th>
                  {{ __('expense.supplier') }}
              </th>
              <th>
                  {{ __('expense.total') }}
              </th>
          </tr>
      </thead>
      <tbody>
          @foreach ($expenses as $expense)
              <tr class="hover">
                  <td>
                      {{ $expense->created_at->format('d/m/Y H:i') }}
                  </td>
                  <td>
                      {{ $expense->supplier->name }}
                  </td>
                  <td>
                      ${{ number_format($expense->total, 2) }}
                  </td>
              </tr>
          @endforeach

      </tbody>
  </table>

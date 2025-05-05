@php

@endphp
<table>
    <thead>
        <tr>
            <th width="20"  align="center"  style="background-color: #e1e2ff;" >CEDULA IDENTIDAD</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >NOMBRES Y APELLIDOS</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($report as $item)
        <tr>
          <td align="center" >{{ $item->cedula_identidad }}</td>
          <td align="center" >{{ $item->nombres_apellidos }}</td>
        </tr>
      @endforeach
    </tbody>
</table>



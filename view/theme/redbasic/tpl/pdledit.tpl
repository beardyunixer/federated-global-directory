<h1>{{$header}}</h1>

<h2>{{$mname}} {{$module}}</h2>

<br />
<a href="help/Comanche" target="redmatrix-help">{{$help}}</a>
<br />
<br />



<form action="pdledit" method="post" >
<input type="hidden" name="module" value="{{$module}}" />
<textarea rows="24" cols="80" name="content">{{$content}}</textarea>

<br />
<input type="submit" name="submit" value="{{$submit}}" />

</form>



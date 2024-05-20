<div class="email-head">
    <div class="email-head-subject">
        <div class="title"><span>Report</span>
    </div>
    </div>
</div>
<div class="row mt-4">
  <form method="get" action="{{url('genratePdf/'.$project->id)}}">
    <input type="text" value="{{$project->id}}"/>
    <button type="submit">Submit</button>
  </form>
</div>
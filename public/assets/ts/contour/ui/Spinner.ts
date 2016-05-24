/**
 * Created by Jason on 5/23/2016.
 */
var template = `
<div class="fancy_spinner">
  <svg class="loader">
    <filter id="blur">
      <fegaussianblur in="SourceGraphic" stddeviation="2"></fegaussianblur>
    </filter>
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#F4F519" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-2">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#DE2FFF" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-3">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#FF5932" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-4">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#E97E42" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-5">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="white" stroke-width="6" stroke-linecap="round" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-6">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#00DCA3" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-7">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="purple" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
  <svg class="loader loader-8">
    <circle cx="75" cy="75" r="60" fill="transparent" stroke="#AAEA33" stroke-width="6" stroke-linecap="round" stroke-dasharray="385" stroke-dashoffset="385" filter="url(#blur)"></circle>
  </svg>
</div>
`;

var style = `
<style type="text/css">

.fancy_spinner {
    position: absolute;
    margin: 50px auto;
    width: 150px;
    top: calc(50% - 100px);
    left: 50%;
    transform: translate(-50%,-50%);
}

.loader {
  position: absolute;
  opacity: .7;
}

.loader circle {
  animation: draw 4s infinite ease-in-out;
  transform-origin: center;
  transform: rotate(-90deg);
}

.loader-2 circle,
.loader-6 circle {
  animation-delay: 1s;
}

.loader-7 circle {
  animation-delay: 2s;
}

.loader-4 circle, 
.loader-8 circle {
  animation-delay: 3s;
}

.loader-3 {
  left: -150px;
  transform: rotateY(180deg);
}

.loader-6,
.loader-7,
.loader-8 {
  left: -150px;
  transform: rotateX(180deg) rotateY(180deg);
}

.loader-5 circle {
  opacity: .2;
}

@keyframes draw {
  50% {
    stroke-dashoffset: 0;
    transform: scale(.5);
  }
}
</style>
`;

export class Spinner
{
    protected parent : JQuery;
    protected spinner : JQuery;
    protected style : JQuery;
    protected playing : JQuery;

    constructor(parent: JQuery)
    {
        this.spinner = $(template);
        this.style = $(style);
        this.parent = parent;
        $("body").append(this.style);
        this.spinner.hide();
        this.parent.append(this.spinner);
        window["spinner"] = this;
    }

    public start(duration : number)
    {
        this.spinner.fadeIn({
            duration: duration
        });
    }
    public stop(duration : number)
    {
        this.spinner.fadeOut({
            duration: duration
        });
    }
}
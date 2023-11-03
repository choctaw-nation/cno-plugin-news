!function(){"use strict";let t=(()=>{class t extends HTMLElement{constructor(){super(),this.iframeLoaded=!1,this.setupDom()}static get observedAttributes(){return["videoid"]}connectedCallback(){this.addEventListener("pointerover",t.warmConnections,{once:!0}),this.addEventListener("click",(()=>this.addIframe()))}get videoId(){return encodeURIComponent(this.getAttribute("videoid")||"")}set videoId(t){this.setAttribute("videoid",t)}get videoTitle(){return this.getAttribute("videotitle")||"Video"}set videoTitle(t){this.setAttribute("videotitle",t)}get videoPlay(){return this.getAttribute("videoPlay")||"Play"}set videoPlay(t){this.setAttribute("videoPlay",t)}get videoStartAt(){return this.getAttribute("videoPlay")||"0s"}set videoStartAt(t){this.setAttribute("videoPlay",t)}get autoLoad(){return this.hasAttribute("autoload")}set autoLoad(t){t?this.setAttribute("autoload",""):this.removeAttribute("autoload")}get autoPlay(){return this.hasAttribute("autoplay")}set autoPlay(t){t?this.setAttribute("autoplay","autoplay"):this.removeAttribute("autoplay")}setupDom(){this.attachShadow({mode:"open"}).innerHTML='\n      <style>\n        :host {\n          contain: content;\n          display: block;\n          position: relative;\n          width: 100%;\n          padding-bottom: calc(100% / (16 / 9));\n        }\n\n        #frame, #fallbackPlaceholder, iframe {\n          position: absolute;\n          width: 100%;\n          height: 100%;\n        }\n\n        #frame {\n          cursor: pointer;\n        }\n\n        #fallbackPlaceholder {\n          object-fit: cover;\n        }\n\n        #frame::before {\n          content: \'\';\n          display: block;\n          position: absolute;\n          top: 0;\n          background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAADGCAYAAAAT+OqFAAAAdklEQVQoz42QQQ7AIAgEF/T/D+kbq/RWAlnQyyazA4aoAB4FsBSA/bFjuF1EOL7VbrIrBuusmrt4ZZORfb6ehbWdnRHEIiITaEUKa5EJqUakRSaEYBJSCY2dEstQY7AuxahwXFrvZmWl2rh4JZ07z9dLtesfNj5q0FU3A5ObbwAAAABJRU5ErkJggg==);\n          background-position: top;\n          background-repeat: repeat-x;\n          height: 60px;\n          padding-bottom: 50px;\n          width: 100%;\n          transition: all 0.2s cubic-bezier(0, 0, 0.2, 1);\n          z-index: 1;\n        }\n        /* play button */\n        .lvo-playbtn {\n          width: 70px;\n          height: 46px;\n          background-color: #212121;\n          z-index: 1;\n          opacity: 0.8;\n          border-radius: 10%;\n          transition: all 0.2s cubic-bezier(0, 0, 0.2, 1);\n          border: 0;\n        }\n        #frame:hover .lvo-playbtn {\n          background-color: rgb(98, 175, 237);\n          opacity: 1;\n        }\n        /* play button triangle */\n        .lvo-playbtn:before {\n          content: \'\';\n          border-style: solid;\n          border-width: 11px 0 11px 19px;\n          border-color: transparent transparent transparent #fff;\n        }\n        .lvo-playbtn,\n        .lvo-playbtn:before {\n          position: absolute;\n          top: 50%;\n          left: 50%;\n          transform: translate3d(-50%, -50%, 0);\n        }\n\n        /* Post-click styles */\n        .lvo-activated {\n          cursor: unset;\n        }\n\n        #frame.lvo-activated::before,\n        .lvo-activated .lvo-playbtn {\n          display: none;\n        }\n      </style>\n      <div id="frame">\n        <picture>\n          <source id="webpPlaceholder" type="image/webp">\n          <source id="jpegPlaceholder" type="image/jpeg">\n          <img id="fallbackPlaceholder"\n               referrerpolicy="origin"\n               width="1100"\n               height="619"\n               decoding="async"\n               loading="lazy">\n        </picture>\n        <button class="lvo-playbtn"></button>\n      </div>\n    ',this.domRefFrame=this.shadowRoot.querySelector("#frame"),this.domRefImg={fallback:this.shadowRoot.querySelector("#fallbackPlaceholder"),webp:this.shadowRoot.querySelector("#webpPlaceholder"),jpeg:this.shadowRoot.querySelector("#jpegPlaceholder")},this.domRefPlayButton=this.shadowRoot.querySelector(".lvo-playbtn")}setupComponent(){this.initImagePlaceholder(),this.domRefPlayButton.setAttribute("aria-label",`${this.videoPlay}: ${this.videoTitle}`),this.setAttribute("title",`${this.videoPlay}: ${this.videoTitle}`),this.autoLoad&&this.initIntersectionObserver()}attributeChangedCallback(t,e,o){"videoid"===t&&e!==o&&(this.setupComponent(),this.domRefFrame.classList.contains("lvo-activated")&&(this.domRefFrame.classList.remove("lvo-activated"),this.shadowRoot.querySelector("iframe").remove()))}addIframe(){if(!this.iframeLoaded){const t=this.autoLoad&&this.autoPlay||!this.autoLoad?"autoplay=1":"",e=`\n<iframe frameborder="0"\n  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"\n  allowfullscreen src="${new URL(`/video/${this.videoId}?${t}&#t=${this.videoStartAt}`,"https://player.vimeo.com/")}"></iframe>`;this.domRefFrame.insertAdjacentHTML("beforeend",e),this.domRefFrame.classList.add("lvo-activated"),this.iframeLoaded=!0}}async initImagePlaceholder(){t.addPrefetch("preconnect","https://i.vimeocdn.com/");const e=`https://vimeo.com/api/v2/video/${this.videoId}.json`,o=(await(await fetch(e)).json())[0].thumbnail_large,i=o.substr(o.lastIndexOf("/")+1).split("_")[0],n=`https://i.vimeocdn.com/video/${i}.webp?mw=1100&mh=619&q=70`,a=`https://i.vimeocdn.com/video/${i}.jpg?mw=1100&mh=619&q=70`;this.domRefImg.webp.srcset=n,this.domRefImg.jpeg.srcset=a,this.domRefImg.fallback.src=a,this.domRefImg.fallback.setAttribute("aria-label",`${this.videoPlay}: ${this.videoTitle}`),this.domRefImg.fallback.setAttribute("alt",`${this.videoPlay}: ${this.videoTitle}`)}initIntersectionObserver(){"IntersectionObserver"in window&&"IntersectionObserverEntry"in window&&new IntersectionObserver(((e,o)=>{e.forEach((e=>{e.isIntersecting&&!this.iframeLoaded&&(t.warmConnections(),this.addIframe(),o.unobserve(this))}))}),{root:null,rootMargin:"0px",threshold:0}).observe(this)}static addPrefetch(t,e,o){const i=document.createElement("link");i.rel=t,i.href=e,o&&(i.as=o),i.crossOrigin="true",document.head.append(i)}static warmConnections(){t.preconnected||(t.addPrefetch("preconnect","https://f.vimeocdn.com"),t.addPrefetch("preconnect","https://player.vimeo.com"),t.addPrefetch("preconnect","https://i.vimeocdn.com"),t.preconnected=!0)}}return t.preconnected=!1,t})();customElements.define("lite-vimeo",t)}();
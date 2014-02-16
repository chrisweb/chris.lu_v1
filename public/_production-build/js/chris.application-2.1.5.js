define(["jquery","chris.library","headroom","blocksit","prettify","jquery.hoverdir"],function(e,t,n){var r=function(){t.log("# initializeHeadroom");var n=e('header[role="banner"]');e.each(n,function(e,t){var n=new Headroom(t,{tolerance:10,offset:10,classes:{initial:"animated",pinned:"swingInX",unpinned:"swingOutX"}});n.init()})},i=function(){t.log("# cssModifier");var n=e(".ui-page-active"),r=n.width(),i=n.find("div#main"),o=n.find("aside#right-side");s(i,o,r),e(window).resize(function(){r=e(".ui-page-active").width(),s(i,o,r)})},s=function(e,n,r){t.log("responsive classes ..."),r<=767?(e.hasClass("col-12")&&e.removeClass("col-12 col-sm-8 col-lg-8"),n.hasClass("col-6")&&n.removeClass("col-6 col-sm-4 col-lg-4")):(e.hasClass("col-12")||e.addClass("col-12 col-sm-8 col-lg-8"),n.hasClass("col-6")||n.addClass("col-6 col-sm-4 col-lg-4"))},o=function(n){t.log("# initializeReadinglist");var r="readinglist_container",i=e("#"+n+" ."+r).width();t.log("containerWidth: "+i);var s=3;i<727&&(s=2),i<654&&
(s=1),e("#"+n+" ."+r).BlocksIt({numOfCol:s,offsetX:0,offsetY:0,blockElement:".readinglist_box"}),t.log("blocksit got intialized"),t.isTouchDevice()||(e("."+r+" > .readinglist_box").each(function(){e(this).hoverdir({hoverDelay:5})}),t.log("hoverdir got intialized"))},u=function(n){t.log("# initializeBookmarks");var r=e("#bookmarks_container");r.off(),r.on("tap","a.bookmarks_tags",function(n){n.preventDefault();var r=e(this).attr("data-chris-tag-key");t.log(e(this).attr("href")+"?format=json");var i=e.ajax({url:e(this).attr("href")+"?format=json",type:"GET",dataType:"json"});i.done(function(n){t.log(n);if(e.type(n.results)==="array"&&n.results.length>0){window.scrollTo(0,0);var i=e("section#core-box");i.css("overflow","hidden"),i.css("min-height",i.height());var s="";e.each(n.results,function(e,t){s+="<li>",s+='<a href="'+t.url+'" title="'+t.title+'">',s+=t.title+"<br>"+t.url,s+="</a>",s+="</li>"});var o=e('<div id="bookmarks_list"></div>'),u='<a class="btn btn-primary ui-link bookmarks_back" href="/mybookmarks">BACK</a>'
;o.append(u);var a=e('<ul id="boomarks_'+r+'" data-role="listview" data-autodividers="true" data-inset="true">'+s+"</ul>");o.append(a);var f=e("#tags_list");f.css("width",f.width()),o.css("width",f.width());var l=i.find("#bookmarks_container");l.css("width",parseInt(i.width())*2+500),f.after(o),f.css("float","left"),o.css("float","left"),a.listview().trigger("create"),f.animate({width:"toggle"},300,function(){})}}),i.fail(function(e,n){t.log("bookmarks request failed: "+n)})}),r.on("tap","a.bookmarks_back",function(n){n.preventDefault(),t.log("#bookmarks_list click BACK"),window.scrollTo(0,0);var r=e("#tags_list");r.find("#bookmarks").find("li").find("a").removeClass(e.mobile.activeBtnClass),r.animate({width:"toggle"},300,function(){var t=e("#bookmarks_list");t.remove()})})},a=function(){t.log("# loadFacebookSDK"),window.fbAsyncInit=function(){FB.init({appId:"424957510901747",status:!0,cookie:!0,xfbml:!1}),FB.XFBML.parse()},function(e,t,n){var r,i=e.getElementsByTagName(t)[0];if(e.getElementById
(n))return;r=e.createElement(t),r.id=n,r.src="//connect.facebook.net/en_US/all.js",i.parentNode.insertBefore(r,i)}(document,"script","facebook-jssdk")},f=function(){t.log("# loadTwitterScript"),!function(e,t,n){var r,i=e.getElementsByTagName(t)[0],s=/^http:/.test(e.location)?"http":"https";e.getElementById(n)||(r=e.createElement(t),r.id=n,r.src=s+"://platform.twitter.com/widgets.js",i.parentNode.insertBefore(r,i))}(document,"script","twitter-wjs"),e.type(twttr)!=="undefined"&&twttr.widgets.load()},l=function(){t.log("# initializeGoogleAnalytics"),typeof _gaq!="undefined"&&(_gaq.push(["_setAccount","UA-16705563-1"]),hash=location.hash,t.log("found hash: "+hash),hash?_gaq.push(["_trackPageview",hash.substr(1)]):_gaq.push(["_trackPageview"]))},c=function(){t.log("# initializeGoogleSDK"),function(){var e=document.createElement("script");e.type="text/javascript",e.async=!0,e.src="https://apis.google.com/js/platform.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore
(e,t)}()},h=function(){t.log("# autoPopulateReadinglistForm");var n=e("#url").val();t.log(n),e("#alert_box").empty();if(n)e.post("/readinglist/admin/ajax/getwebsitedata?format=json",{articleurl:n},function(n){t.log(n.websiteData,"data.websiteData"),t.log(n.websiteData.description,"description"),t.log(n.websiteData.title,"title"),t.log(n.websiteData.favicon,"favicon"),t.log(n.websiteData.image,"image"),t.log(n.websiteData.domain,"domain");if(!n.websiteData.error)e("#title").val(n.websiteData.title),e("#headline").val(n.websiteData.description),e("#imageUrl").val(n.websiteData.image),e("#favicon").val(n.websiteData.favicon),e("#domain").val(n.websiteData.domain);else{var r=e("<div>",{"class":"alert alert-error",text:n.websiteData.error});e("#alert_box").html(r)}},"json");else{var r=e("<div>",{"class":"alert alert-error",text:"No URL given"});e("#alert_box").html(r)}};return{initializeApp:function(){t.log("app got intialized")},initializeReadinglist:o,initializeBookmarks:u,loadFacebookSDK:
a,initializeGoogleAnalytics:l,autoPopulateReadinglistForm:h,cssModifier:i,initializeGoogleSDK:c,loadTwitterScript:f,initializeHeadroom:r}});
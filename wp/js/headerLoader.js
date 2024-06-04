//@ts-check
window.addEventListener('load', async (event) => {
    const response = await this.fetch("header.html");
    const data = await response.text();
    let tmp = this.document.createElement("body");
    tmp.innerHTML = data;
    let headerHTML = this.document.querySelector("header");
    if (headerHTML == void (0)) {
        throw "header要素がありません";
    }
    let tmp2 = tmp.querySelector("header");
    if (tmp2 == void(0)) {
        throw "headerが生成できませんでした";
    }
    headerHTML.innerHTML = tmp2.innerHTML;
    window.dispatchEvent(new Event('OnHeaderLoad'));
})
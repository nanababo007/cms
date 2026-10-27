"use strict";

/* =========================================
   1. 요소 및 데이터 정의
========================================= */

const STORAGE_KEY = "html_utility_items_v1";

const sourceText = document.getElementById("sourceText");
const itemList = document.getElementById("itemList");
const itemCount = document.getElementById("itemCount");

const titleInput = document.getElementById("title");
const statusSelect = document.getElementById("status");
const statusIndicator = document.getElementById("statusIndicator");
const detailContent = document.getElementById("detailContent");
const memoInput = document.getElementById("memo");

const selectedInfo = document.getElementById("selectedInfo");
const saveMessage = document.getElementById("saveMessage");

const STATUS_NAMES = {
    wait: "대기",
    progress: "진행중",
    complete: "완료",
    stop: "강제종료"
};

let items = [];
let selectedId = null;

/*
데이터 구조

{
    id: 고유 ID,
    title: 제목,
    status: 진행 상태,
    memo: 메모
}
*/


/* =========================================
   2. 데이터 저장 및 불러오기
========================================= */

function saveToStorage() {
    try {
        sessionStorage.setItem(
            STORAGE_KEY,
            JSON.stringify(items)
        );
        return true;
    } catch (error) {
        console.error("세션 저장 실패:", error);
        alert("세션 저장에 실패했습니다. 브라우저 저장 공간을 확인하세요.");
        return false;
    }
}

function loadFromStorage() {
    try {
        const saved = sessionStorage.getItem(STORAGE_KEY);

        if (!saved) {
            items = [];
            return;
        }

        const parsed = JSON.parse(saved);

        if (!Array.isArray(parsed)) {
            throw new Error("올바르지 않은 데이터 형식");
        }

        items = parsed.filter(item =>
            item &&
            typeof item.id === "string" &&
            typeof item.title === "string" &&
            Object.prototype.hasOwnProperty.call(
                STATUS_NAMES,
                item.status
            )
        ).map(item => ({
            id: item.id,
            title: item.title,
            status: item.status,
            memo: typeof item.memo === "string" ? item.memo : ""
        }));

    } catch (error) {
        console.error("세션 데이터 읽기 실패:", error);
        items = [];
        alert("저장된 데이터를 읽지 못했습니다. 빈 목록으로 시작합니다.");
    }
}

function createId() {
    if (window.crypto && typeof window.crypto.randomUUID === "function") {
        return window.crypto.randomUUID();
    }

    return Date.now().toString(36) + "-" +
        Math.random().toString(36).slice(2);
}


/* =========================================
   3. 문장 정리
========================================= */

/*
처리 규칙
- 줄바꿈을 기준으로 항목 분리
- 각 줄의 앞뒤 공백 제거
- 빈 줄 제거
- 두 줄 이상 연속된 빈 줄은 결과적으로 제거
- 정리 후 각 줄을 하나의 목록 항목으로 사용
*/

function normalizeLines(text) {
	if (!text) return '';
	//---
	text = text.replaceAll('\t',' ');
	for(var i=0;i<20;i++){
		text = text.replaceAll('  ',' ');
	} //for
	//---
    return text
        .replace(/\r\n?/g, "\n")
        .split("\n")
        .map(line => line.trim())
        .filter(line => line.length > 0);
}

document.getElementById("cleanButton").addEventListener("click", () => {
    const lines = normalizeLines(sourceText.value);
    sourceText.value = lines.join("\n");
});


/* =========================================
   4. 목록 렌더링 및 상태 색상
========================================= */

function getSelectedItem() {
    return items.find(item => item.id === selectedId) || null;
}

function renderList() {
    const previousId = selectedId;

    itemList.replaceChildren();

    items.forEach(item => {
        const option = document.createElement("option");

        option.value = item.id;
        option.textContent = item.title;
        option.className = "status-" + item.status;
        option.title = item.title;

        itemList.appendChild(option);
    });

    itemCount.textContent = items.length + "개";

    if (previousId && items.some(item => item.id === previousId)) {
        itemList.value = previousId;
    } else {
        itemList.selectedIndex = -1;
    }
}

function refreshStatusColor() {
    statusIndicator.className =
        "status-indicator status-" + statusSelect.value;
}


/* =========================================
   5. 상세 정보 표시
========================================= */

function showEmptyDetail() {
    selectedId = null;

    titleInput.value = "";
    statusSelect.value = "wait";
    memoInput.value = "";

    detailContent.textContent = "목록에서 항목을 선택하세요.";
    selectedInfo.textContent = "선택 항목 없음";
    saveMessage.textContent = "저장 전";

    refreshStatusColor();
}

function loadDetail(item) {
    if (!item) {
        showEmptyDetail();
        return;
    }

    selectedId = item.id;

    titleInput.value = item.title;
    statusSelect.value = item.status;
    memoInput.value = item.memo;

    /*
    제목과 동일한 목록 문자열을 정보 내용에 표시합니다.
    textContent를 사용해 HTML 문자열 실행을 방지합니다.
    */
    detailContent.textContent = item.title;

    selectedInfo.textContent = "선택됨";
    saveMessage.textContent = "저장 전";

    refreshStatusColor();
}

itemList.addEventListener("change", () => {
    const item = items.find(
        entry => entry.id === itemList.value
    );

    loadDetail(item);
});


/* =========================================
   6. 상단 문장 목록 등록
========================================= */

document.getElementById("registerButton").addEventListener("click", () => {
    const lines = normalizeLines(sourceText.value);

    if (lines.length === 0) {
        alert("등록할 문장을 입력하세요.");
        sourceText.focus();
        return;
    }

    /*
    앞뒤 공백을 제거한 제목 기준으로
    기존 항목과 이번 입력 안의 중복을 모두 확인합니다.
    */
    const existingTitles = new Set(
        items.map(item => item.title)
    );

    let addedCount = 0;
    let duplicateCount = 0;

    lines.forEach(line => {
        if (existingTitles.has(line)) {
            duplicateCount++;
            return;
        }

        items.push({
            id: createId(),
            title: line,
            status: "wait",
            memo: ""
        });

        existingTitles.add(line);
        addedCount++;
    });

    if (!saveToStorage()) {
        // 저장 실패 시 이번 등록을 취소하고 기존 상태로 복원
        loadFromStorage();
        renderList();
        showEmptyDetail();
        return;
    }

    renderList();

    if (addedCount > 0) {
        const lastItem = items[items.length - 1];
        itemList.value = lastItem.id;
        loadDetail(lastItem);
    }

    alert(
        "등록 처리 완료\n\n" +
        "신규 등록: " + addedCount + "개\n" +
        "중복 제외: " + duplicateCount + "개"
    );
});


/* =========================================
   7. 목록 문자열 오름차순 정렬
========================================= */

document.getElementById("sortButton").addEventListener("click", () => {
    items.sort((a, b) =>
        a.title.localeCompare(b.title, "ko", {
            numeric: true,
            sensitivity: "base"
        })
    );

    if (!saveToStorage()) {
        loadFromStorage();
    }

    renderList();

    if (selectedId) {
        itemList.value = selectedId;
    }

    saveMessage.textContent = "정렬 처리";
});


/* =========================================
   8. 상태 변경 시 색상 미리보기
========================================= */

statusSelect.addEventListener("change", () => {
    refreshStatusColor();
});


/* =========================================
   9. 상세 정보 저장
========================================= */

document.getElementById("saveButton").addEventListener("click", () => {
    const item = getSelectedItem();

    if (!item) {
        alert("먼저 좌측 목록에서 항목을 선택하세요.");
        return;
    }

    const newTitle = titleInput.value.trim();

    if (!newTitle) {
        alert("제목을 입력하세요.");
        titleInput.focus();
        return;
    }

    /*
    제목 수정 시 다른 항목과 중복 여부 확인
    */
    const duplicated = items.some(other =>
        other.id !== item.id &&
        other.title === newTitle
    );

    if (duplicated) {
        alert("동일한 제목의 항목이 이미 존재합니다.");
        titleInput.focus();
        return;
    }

    const oldItem = {
        id: item.id,
        title: item.title,
        status: item.status,
        memo: item.memo
    };

    item.title = newTitle;
    item.status = statusSelect.value;
    item.memo = memoInput.value;

    if (!saveToStorage()) {
        Object.assign(item, oldItem);
        loadDetail(item);
        renderList();
        return;
    }

    renderList();
    itemList.value = item.id;

    loadDetail(item);

    saveMessage.textContent =
        "저장 완료 (" + new Date().toLocaleTimeString("ko-KR") + ")";

    alert("상세 정보가 세션 스토리지에 저장되었습니다.");
});


/* =========================================
   10. 입력 취소
========================================= */

document.getElementById("resetButton").addEventListener("click", () => {
    const item = getSelectedItem();

    if (item) {
        loadDetail(item);
    } else {
        showEmptyDetail();
    }
});


/* =========================================
   11. 선택 항목 삭제
========================================= */

document.getElementById("deleteButton").addEventListener("click", () => {
    const item = getSelectedItem();

    if (!item) {
        alert("삭제할 항목을 선택하세요.");
        return;
    }

    if (!confirm(
        "선택한 항목을 삭제하시겠습니까?\n\n" + item.title
    )) {
        return;
    }

    const previousItems = items;
    const previousId = selectedId;

    items = items.filter(entry => entry.id !== item.id);
    selectedId = null;

    if (!saveToStorage()) {
        items = previousItems;
        selectedId = previousId;
        renderList();
        itemList.value = previousId;
        loadDetail(getSelectedItem());
        return;
    }

    renderList();
    showEmptyDetail();
});


/* =========================================
   12. 초기 실행
========================================= */

function init() {
    loadFromStorage();
    renderList();
    showEmptyDetail();
}

init();
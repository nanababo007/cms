var g_reply2ItemTemplateString = `
	<div class="reply-item-class" id="reply2ListItemArea{{bdr2Seq}}" data-bdr_seq="{{bdrSeq}}" data-bdr2_seq="{{bdr2Seq}}">
		<hr />
		{{reply2Datetime}} / 
		<a href="javascript:g_reply2ObjectList['{{bdrSeqId}}'].modifyReply2Form('{{bdr2Seq}}','{{bdr2SeqId}}');">수정</a> | 
		<a href="javascript:g_reply2ObjectList['{{bdrSeqId}}'].deleteReply2('{{bdr2Seq}}','{{bdr2SeqId}}');" style="color:red;">삭제</a> | 
		<a href="javascript:g_reply2ObjectList['{{bdrSeqId}}'].fixReply2('{{bdr2Seq}}','{{bdr2FixYN}}','{{bdr2SeqId}}');">고정</a>
		<div id="reply2ItemView{{bdr2SeqId}}">{{reply2Content}}</div>
		<div id="reply2ItemEdit{{bdr2SeqId}}" style="display:none;margin-top:6px;">
			<textarea id="reply2ItemEditText{{bdr2SeqId}}" style="width:80%;height:100px;">{{orgReply2Content}}</textarea>
			<div>
				<input type="button" onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].modifyReply2('{{bdr2Seq}}','{{bdr2SeqId}}');" value="댓글수정" />
				<input type="button" onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].cancelModifyReply2Form('{{bdr2Seq}}','{{bdr2SeqId}}');" value="댓글수정 취소" />
			</div>
		</div>
	</div>
`;
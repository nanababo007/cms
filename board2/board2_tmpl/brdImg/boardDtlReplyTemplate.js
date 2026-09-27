var replyItemTemplateString = `
	<div class="reply-item-class" id="reply2ListItemArea{{bdrSeq}}" data-bdr_seq="{{bdrSeq}}">
		<hr />
		{{replyDatetime}} / 
		<a href="javascript:modifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');">수정</a> | 
		<a href="javascript:deleteReply('{{bdrSeq}}','{{bdrSeqId}}');" style="color:red;">삭제</a> | 
		<a href="javascript:fixReply('{{bdrSeq}}','{{bdrFixYN}}','{{bdrSeqId}}');">고정</a> 
		<div id="replyItemView{{bdrSeqId}}">{{replyContent}}</div>
		<div id="replyItemEdit{{bdrSeqId}}" style="display:none;">
			<textarea id="replyItemEditText{{bdrSeqId}}" style="width:80%;height:100px;">{{orgReplyContent}}</textarea>
			<div>
				<input type="button" onclick="javascript:modifyReply('{{bdrSeq}}','{{bdrSeqId}}');" value="댓글수정" />
				<input type="button" onclick="javascript:cancelModifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');" value="댓글수정 취소" />
			</div>
		</div>
		<div id="reply2ListItemArea{{bdrSeqId}}" style="margin-top:10px;padding-left:20px;">
			<div style="margin:0;padding:10px;border:1px solid #dadada;background-color:#fafafa;">
				<h4 style="margin:0;padding:0;">하위댓글 ({{reply2Cnt}}개)</h4>
				<div class="reply-area-class">
					<textarea style="width:99.4%;height:100px;margin-top:10px;" placeholder="댓글내용" id="reply2Content{{bdrSeqId}}"></textarea>
					<button onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].writeReply2();">하위댓글등록</button>
					<button onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].cancelReply2();">하위댓글취소</button>
					<div class="reply2-list-area-class"></div>
				</div>
			</div>
		</div>
	</div>
`;
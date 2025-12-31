var replyItemTemplateString = `
	<div class="reply-item-class" data-bdr_seq="{{bdrSeq}}">
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
	</div>
`;
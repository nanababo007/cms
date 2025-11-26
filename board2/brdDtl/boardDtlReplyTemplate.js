var replyItemTemplateString = `
	<div class="reply-item-class" data-bdr_seq="{{bdrSeq}}">
		<hr />
		{{replyDatetime}} / 
		<a href="javascript:modifyReplyForm('{{bdrSeq}}');">수정</a> | 
		<a href="javascript:deleteReply('{{bdrSeq}}');" style="color:red;">삭제</a>
		<div id="replyItemView{{bdrSeq}}">{{replyContent}}</div>
		<div id="replyItemEdit{{bdrSeq}}" style="display:none;">
			<textarea id="replyItemEditText{{bdrSeq}}" style="width:80%;height:100px;">{{orgReplyContent}}</textarea>
			<div>
				<input type="button" onclick="javascript:modifyReply('{{bdrSeq}}');" value="댓글수정" />
				<input type="button" onclick="javascript:cancelModifyReplyForm('{{bdrSeq}}');" value="댓글수정 취소" />
			</div>
		</div>
	</div>
`;